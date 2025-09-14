<x-layouts.admin.app>
    <x-page-header>
        <h1 class="text-center w-100">Site Settings</h1>
    </x-page-header>

    <div class="container-fluid container-lg backup">
        <form>
            @csrf
            @method('PUT')

            <div class="mt-4 d-flex justify-content-between">
                <div class="pt-0 pt-md-2 ">
                    <a href="{{route('admin.settings.logo-site')}}" target="_self" class="me-3">Logo & Site
                        Name</a>
                    <a href="{{route('admin.settings.font-colors')}}" target="_self" class="me-3 ">Font &
                        Colors</a>
                    <a href="{{route('admin.settings.features')}}" class="me-3 active color" target="_self">Features</a>
                    <a href="{{route('admin.settings.information')}}" class="" target="_self">Information</a>
                </div>
                <div class=" d-flex">
                    <img src="{{ asset('assets/admin/images/group1000008180.png') }}" class="align-self-center me-4"
                        alt="question">
                    <img src="{{ asset('assets/admin/images/vector11.png') }}" class="align-self-center me-4"
                        alt="paper">
                    <button type="button" class="button_Adduser">Save Changes</button>
                </div>
            </div>

            <div class="p-4 mt-3 mb-3 overflow-hidden border shadow rounded-3">
                <div class="pb-2 info_security">
                    <h4 class="mb-4">Features</h4>
                </div>
                <div class="container-box">

                    <!-- Site Title -->
                    <div class="my-4 row ">
                        <label class="col-md-4 col-form-label form-label font">Site Title</label>
                        <div class="col-md-8">
                            <select class="form-select w-75 text-muted">
                                <option>Websites name here</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tagline -->
                    <div class="my-4 row">
                        <label class="col-md-4 col-form-label form-label font">Tagline</label>
                        <div class="col-md-8">
                            <select class="form-select w-75 text-muted">
                                <option>Book type</option>
                            </select>
                            <p class="mt-2 w-75 font-main">In a few words, explain what this site is about.
                                Example: “Historical story”</p>
                        </div>
                    </div>

                    <!-- Site Icon -->
                    <div class="my-4 row">
                        <div class="col-md-4">
                            <label class="col-md-4 col-form-label form-label font">Site icon</label>
                        </div>

                        <div class="mx-auto mb-3 col-md-8">
                            <div class="create_book_feau">
                                <h4 class="mt-2 text-center align-self-center font">Chosse a Site Icon</h4>
                            </div>
                            <p class="mt-2 w-75 font-main">The Site Icon is what you see in browser tabs,
                                bookmark bars, and within the sites. It should be square and at least 512 by 512
                                pixels.</p>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label form-label font">Administration Email
                                Address</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control w-75" placeholder="example@gmail.com">
                                <p class="mt-1 w-75 font-main">This address is used for admin purposes. If you
                                    change this, an email will be sent to confirm it.</p>
                            </div>
                        </div>

                        <!-- Membership -->
                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label form-label font">Membership</label>
                            <div class="col-md-8">
                                <div class="form-check w-75">
                                    <input class="form-check-input" type="checkbox">
                                    <p class="pt-1 text-black">Anyone can register</p>
                                </div>
                            </div>
                        </div>

                        <!-- User Default Role -->
                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label form-label font">New User Default Role</label>
                            <div class="col-md-8">
                                <select class="form-select w-75 text-muted">
                                    <option>Subscriber</option>
                                </select>
                            </div>
                        </div>

                        <!-- Site Language -->
                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label form-label font">Site Language</label>
                            <div class="col-md-8">
                                <select class="form-select w-75 text-muted">
                                    <option>English (United States)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Timezone -->
                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label form-label font">Timezone</label>
                            <div class="col-md-8">
                                <select class="form-select w-75 text-muted ">
                                    <option>UTC+0</option>
                                </select>
                                <p class="mt-2 w-75 font-main">Choose either a city in the same timezone as you
                                    or a UTC (Coordinated Universal Time) time offset.</p>
                                <p class="mt-2 text-dark font-main2">Universal time is <span
                                        class="text-primary">2024-12-24 05 : 24 : 48</span></p>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="text-start">
                            <button class="button_Adduser">Save Changes</button>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</x-layouts.admin.app>