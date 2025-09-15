<x-layouts.dashboard.app>
    <div class="home px-4">
        <div class="profile-managment ">
            <h4 class="pt-4 pb-2">Profile Management</h4>
            <div class="bg-white profile-manage rounded-4">
                <div class="p-4 name-manage border-bottom d-flex justify-content-between">
                    <h5 class="align-self-center">Name</h5>
                    <h5 class="pt-2 align-content-start">{{ auth()->user()->fname }}</h5>
                    {{-- <button class="button_change">Change</button> --}}

                </div>
                <div class="p-4 name-manage border-bottom d-flex justify-content-between">
                    <h5 class="align-self-center">Email</h5>
                    <h5 class="pt-2 ">{{ auth()->user()->email }}</h5>
                </div>
                @foreach ($user->subscriptions as $subscription)
                    <div class="p-4 name-manage border-bottom d-flex justify-content-between">
                        <h5 class="align-self-center">Total Books</h5>
                        <h5 class="pt-2 ">{{ $subscription->used_books }}</h5>
                    </div>
                @endforeach
                @foreach ($user->subscriptions as $subscription)
                    <div class="p-4 name-manage border-bottom d-flex justify-content-between">
                        <h5 class="align-self-center">used books</h5>
                        <h5 class="pt-2 ">{{ $subscription->used_words }}</h5>
                    </div>
                @endforeach


            </div>


        </div>
    </div>
</x-layouts.dashboard.app>