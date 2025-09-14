<x-layouts.admin.app>
    <x-page-header>
        <h1 class="text-center w-100">Site Settings</h1>
    </x-page-header>

    <div class="container-fluid container-lg backup">
        <div class="mt-4 d-flex justify-content-between">
            <div class="pt-0 pt-md-2 ">
                <a href="{{route('admin.settings.logo-site')}}" target="_self" class="me-3">Logo & Site
                    Name</a>
                <a href="{{route('admin.settings.font-colors')}}" target="_self" class="me-3">Font & Colors</a>
                <a href="{{route('admin.settings.features')}}" class="me-3" target="_self">Features</a>
                <a href="{{route('admin.settings.information')}}" class="active color" target="_self">Information</a>
            </div>
            <div class=" d-flex">
                <img src="{{ asset('assets/admin/images/group1000008180.png') }}" class="align-self-center me-4"
                    alt="question">
                <img src="{{ asset('assets/admin/images/vector11.png') }}" class="align-self-center me-4" alt="paper">

            </div>
        </div>

        <form action="{{ route('admin.settings.information') }}" method="POST"
            class="p-4 mt-3 mb-3 overflow-hidden border shadow rounded-3">
            @csrf
            @method('PUT')

            <div class="pb-2 info_security">
                <h4 class="mb-4">Information</h4>
            </div>

            <div class="container-box">
                <!-- Address -->
                <div class="mt-4 mb-4 row">
                    <label class="col-md-4 col-form-label form-label font">
                        Address
                    </label>

                    <div class="col-md-8">
                        <input type="text" name="address" class="form-control w-75" value="{{ $address?->value }}">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Contact Email -->
                <div class="mt-4 mb-4 row">
                    <label class="col-md-4 col-form-label form-label font">
                        Contact Email
                    </label>

                    <div class="col-md-8">
                        <input type="email" name="contact_email" class="form-control w-75"
                            placeholder="support@example.com" value="{{ $contactEmail?->value }}">
                        @error('contact_email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <!--Facebook -->
                <div class="mt-4 mb-4 row">
                    <label class="col-md-4 col-form-label form-label font">
                        Facebook
                    </label>

                    <div class="col-md-8">
                        <input type="text" name="facebook" class="form-control w-75" value="{{ $facebook?->value }}">
                        @error('facebook')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Twitter -->
                <div class="mt-4 mb-4 row">
                    <label class="col-md-4 col-form-label form-label font">
                        Twitter or ( X )
                    </label>

                    <div class="col-md-8">
                        <input type="text" name="twitter" class="form-control w-75" value="{{ $twitter?->value }}">
                        @error('twitter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Youtube -->
                <div class="mt-4 mb-4 row">
                    <label class="col-md-4 col-form-label form-label font">
                        Youtube
                    </label>

                    <div class="col-md-8">
                        <input type="text" name="youtube" class="form-control w-75" value="{{ $youtube?->value }}">
                        @error('youtube')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="mt-4 mb-2 button_Adduser">Save Changes</button>
        </form>
    </div>
</x-layouts.admin.app>