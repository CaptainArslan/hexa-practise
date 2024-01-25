<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-gray-200">
                    <x-label for="Dashboard" :value="__('Send Notification')" />
                </div>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('send.push.notification') }}" class="sm:px-6 lg:px-8 py-6 lg:py-8">
                    @csrf

                    <!-- title -->
                    <div>
                        <x-label for="title" :value="__('Title')" />
                        <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-label for="body" :value="__('Body')" />
                        <x-input id="body" class="block mt-1 w-full" type="text" name="body" :value="old('body')" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Send Notification') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-messaging.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyAQ-KR9elriqrlkZr4cGe_VAm-vGjzq6ss",
            authDomain: "pocket-filler-b78b5.firebaseapp.com",
            projectId: "pocket-filler-b78b5",
            storageBucket: "pocket-filler-b78b5.appspot.com",
            messagingSenderId: "116142330599",
            appId: "1:116142330599:web:40102ba726dcf33209c842",
            measurementId: "G-QJTR32BC05"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function() {
                    // return messaging.getToken({
                    //     vapidKey: 'Your_Public_Key'
                    // })
                    return messaging.getToken();
                })
                .then(function(token) {
                    // console.log("device token -> " + token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route("save.device.token") }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(err) {
                            console.log('User Chat Token Error' + err);
                        },
                    });

                }).catch(function(err) {
                    console.log('User Chat Token Error' + err);
                });
        }

        initFirebaseMessagingRegistration();

        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });
    </script>

</x-app-layout>