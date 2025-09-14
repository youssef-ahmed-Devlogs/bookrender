<x-layouts.admin.app>
    <x-page-header>
        <h1 class="text-center w-100">Site Settings</h1>
    </x-page-header>

    <div class="container-fluid container-lg backup">
        <form action="{{route('admin.settings.font-colors')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mt-4 d-flex justify-content-between">
                <div class="pt-0 pt-md-2 ">
                    <a href="{{route('admin.settings.logo-site')}}" target="_self" class="me-3">Logo & Site
                        Name</a>
                    <a href="{{route('admin.settings.font-colors')}}" target="_self" class="me-3 active color">Font &
                        Colors</a>
                    <a href="{{route('admin.settings.features')}}" class="me-3" target="_self">Features</a>
                    <a href="{{route('admin.settings.information')}}" class="" target="_self">Information</a>
                </div>
                <div class=" d-flex">
                    <img src="{{ asset('assets/admin/images/group1000008180.png') }}" class="align-self-center me-4"
                        alt="question">
                    <img src="{{ asset('assets/admin/images/vector11.png') }}" class="align-self-center me-4"
                        alt="paper">
                    <button class="button_Adduser">Save Changes</button>
                </div>
            </div>



            <div class="p-2 my-3 row gx-4 gy-3 flex-lg-nowrap">
                <div class="p-4 shadow col-12 col-md-12 col-lg-6 rounded-4 ">
                    <div class="mb-2">
                        <h3>Font</h3>
                    </div>



                    <p class="pt-3 font">Font Family</p>

                    <select name="fontfamily" class="form-select w-100" id="fontSelect" onchange="applyFont()">
                        <option value="Roboto , sans-serif">Roboto</option>
                        <option value="Open Sans , sans-serif">Open Sans</option>
                        <option value="Lora , sans-serif">Lora</option>
                        <option value="Montserrat , sans-serif">Montserrat</option>
                        <option value="Merriweather , sans-serif">Merriweather</option>
                        <option value="Poppins , sans-serif">Poppins</option>
                        <option value="Raleway , sans-serif">Raleway</option>
                        <option value="Lato , sans-serif">Lato</option>
                        <option value="Ubuntu , sans-serif">Ubuntu</option>
                        <option value="Playfair Display , sans-serif">Playfair Display</option>
                    </select>

                    <P class="pt-4">Font Size</P>

                    <div class="d-flex justify-content-between font-siting ">
                        <div>
                            <div class="mb-4 d-flex ">
                                <h5 class=" ms-md-3 me-5">H1</h5>
                                <div class="number-input">
                                    <input type="" id="number" value="{{$seettings->font_h1}}" name="h1" min="1">
                                    <div class="controls">
                                        {{-- <button onclick="increase()"><i class="fas fa-chevron-up"></i></button>
                                        <button onclick="decrease()"><i class="fas fa-chevron-down"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1 mb-4 d-flex ">
                                <h5 class="mt-1 ms-md-3 me-5 ">H2</h5>
                                <div class="number-input">
                                    <input type="" name="h2" id="number" value="{{$seettings->font_h2}}" min="1">
                                    <div class="controls">
                                        {{-- <button onclick="increase()"><i class="fas fa-chevron-up"></i></button>
                                        <button onclick="decrease()"><i class="fas fa-chevron-down"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1 mb-4 d-flex">
                                <h5 class="mt-1 ms-md-3 me-5 ">H3</h5>
                                <div class="number-input">
                                    <input type="" name="h3" value="{{$seettings->font_h3}}" id="number" value="54"
                                        min="1">
                                    <div class="controls">
                                        {{-- <button onclick="increase()"><i class="fas fa-chevron-up"></i></button>
                                        <button onclick="decrease()"><i class="fas fa-chevron-down"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1 mb-4 d-flex ">
                                <h5 class="mt-1 ms-md-3 me-5 ">H4</h5>
                                <div class="number-input">
                                    <input type="" name="h4" id="number" value="{{$seettings->font_h4}}" min="1">
                                    <div class="controls">
                                        {{-- <button onclick="increase()"><i class="fas fa-chevron-up"></i></button>
                                        <button onclick="decrease()"><i class="fas fa-chevron-down"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="font-color">
                            <div class="mt-1 mb-4 d-flex ">
                                <h5 class="mt-1 ms-4 me-5">H5</h5>
                                <div class="number-input ms-4">
                                    <input name="h5" value="{{$seettings->font_h5}}" type="" id="number" value="54"
                                        min="1">
                                    <div class="controls">
                                        {{-- <button onclick="increase()"><i class="fas fa-chevron-up"></i></button>
                                        <button onclick="decrease()"><i class="fas fa-chevron-down"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1 mb-4 d-flex paragraph-font">
                                <h5 class="mt-1 ms-4 ">Paragrph</h5>
                                <div class="number-input me-5">
                                    <input type="" name="para" id="number" value="54" min="1">
                                    <div class="controls">
                                        {{-- <button onclick="increase()"><i class="fas fa-chevron-up"></i></button>
                                        <button onclick="decrease()"><i class="fas fa-chevron-down"></i></button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 shadow col-12 col-md-12 col-lg-6 rounded-4 ms-lg-4 ms-0 ">
                    <div class="mb-2">
                        <h3>Colors</h3>
                    </div>
                    <p class="">Body background</p>
                    <div class="mb-3 input-group w-100 ">
                        <input name="body" type="text" class="form-control w-75" placeholder="#ffffff">
                        <input type="color" name="body" class="border-black form-control form-control-color"
                            id="exampleColorInput" value="#FFFFFF" title="Choose your color">
                    </div>
                    <p class="">Font Heading</p>
                    <div class="mb-3 input-group w-100 ">
                        <input name="heading" type="text" class="form-control w-75" placeholder="#17253F">
                        <input name="heading" type="color" class="form-control form-control-color"
                            id="exampleColorInput" value="#17253F" title="Choose your color">
                    </div>
                    <p class="">Paragraph</p>
                    <div class="mb-3 input-group w-100 ">
                        <input name="paracolor" type="text" class="form-control w-75" placeholder="#949494">
                        <input type="color" name="paracolor" class="form-control form-control-color"
                            id="exampleColorInput" value="#949494" title="Choose your color">
                    </div>
                    <div class="d-flex justify-content-between w-100">
                        <div class="w-75 me-4">
                            <p class="">Button Color</p>
                            <div class="input-group w-100 ">
                                <input name="button" type="text" class="form-control w-75 " placeholder="#1876F1">
                                <input name="button" type="color" class="form-control form-control-color"
                                    id="exampleColorInput" value="#1876F1" title="Choose your color">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin.app>