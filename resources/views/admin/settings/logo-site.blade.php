<x-layouts.admin.app>
    <x-page-header>
        <h1 class="text-center w-100">Site Settings</h1>
    </x-page-header>

    <div class="container-fluid container-lg backup">
        <div class="mt-4 d-flex justify-content-between">
            <div class="pt-0 pt-md-2 ">
                <a href="{{route('admin.settings.logo-site')}}" target="_self" class="me-3 active color">Logo & Site
                    Name</a>
                <a href="{{route('admin.settings.font-colors')}}" target="_self" class="me-3">Font & Colors</a>
                <a href="{{route('admin.settings.features')}}" class="me-3" target="_self">Features</a>
                <a href="{{route('admin.settings.information')}}" class="" target="_self">Information</a>
            </div>
            <div class=" d-flex">
                <img src="{{ asset('assets/admin/images/group1000008180.png') }}" class="align-self-center me-4"
                    alt="question">
                <img src="{{ asset('assets/admin/images/vector11.png') }}" class="align-self-center me-4" alt="paper">

            </div>
        </div>
        <div class="p-4 mt-3 mb-3 overflow-hidden border shadow  rounded-3">

            <div class="pb-2 info_security">
                <h4>Logo</h4>
            </div>
            
            <form action="{{route('admin.settings.upload-logos')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Logo Upload -->
                <div class="input_group">

                    <div class="mt-4 d-flex">
                        <h5 class="mt-2 me-5 margin_input logo">Logo</h5>
                        <div class="input-group position-relative ms-1">
                            <input type="file" class="p-3 form-control" name="site_logo" accept="image/*">
                            {{-- <i class="p-2 fa-solid fa-trash-can position-absolute rounded-2"></i> --}}
                        </div>
                    </div>

                    <div class="mt-1 d-flex create_book">
                        <img src="{{ asset('storage/' . $logo->logo) }}" class="ms-3" alt="logo">

                        <h4 class="mt-2 text-center me-0 me-md-5 align-self-center">Bookrender</h4>
                    </div>

                    @error('site_logo')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Retina Logo Upload -->
                <div class="mt-5 input_group">
                    <div class="mt-4 d-flex">
                        <h5 class="mt-2 me-3 me-md-5 margin_input2 logo">Retina Logo</h5>
                        <div class="input-group position-relative">
                            <input type="file" class="p-3 form-control" name="site_retinalogo" accept="image/*">
                            {{-- <i class="p-2 fa-solid fa-trash-can position-absolute rounded-2"></i> --}}
                        </div>
                    </div>
                </div>
                <div class="mt-1 d-flex create_book2">
                    <img src="{{ asset('storage/' . $retinalogo->retinalogo) }}" class="ms-3" alt="logo">
                    <h4 class="mt-2 text-center me-5 me-md-5 align-self-center ">Bookrender</h4>
                </div>

                @error('site_retinalogo')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror

                <!-- Submit button -->
                <button type="submit" class="mt-4 mb-2 button_Adduser">Save Changes</button>
            </form>

            <p class="mt-4 ms-2 d-none d-md-block font-main">Retina Logo should be twice size as
                logo</p>

        </div>
    </div>
</x-layouts.admin.app>