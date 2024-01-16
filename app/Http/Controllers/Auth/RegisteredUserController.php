<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create a new User instance and populate its fields
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Check if a profile picture is associated with the user
        $tempFile = TempFile::where('folder', $request->profile)->first();

        if ($tempFile) {
            dd($tempFile);
            // Set the profile picture path for the user
            $user->profile = $tempFile->path;

            // Move the profile picture to the 'avatars' directory
            Storage::move($tempFile->path, $user->profile);

            // Remove the temporary directory
            rmdir(storage_path($tempFile->folder));

            // Delete the temporary file record from the database
            $tempFile->delete();
        }

        // Save the user to the database
        $user->save();

        // Log in the registered user
        Auth::login($user);

        // Dispatch the Registered event
        event(new Registered($user));

        // Redirect the user to the home page
        return redirect(RouteServiceProvider::HOME);
    }
}
