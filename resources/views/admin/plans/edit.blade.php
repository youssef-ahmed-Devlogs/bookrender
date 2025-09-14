<x-layouts.admin.app>
    <x-page-header>
        <h1 class="text-center w-100">Edit Plan</h1>
    </x-page-header>

    <div class="pb-5 container-fluid container-lg backup">

        <div class="mt-3 d-flex justify-content-between">
            <div class="pb-2 mb-1 create_new_plan">
                <a href="{{ route('admin.plans.index') }}" target="_self" class="me-3 ">
                    <i class="fa-solid fa-chevron-left me-3"></i>
                    Edit Plan
                </a>
            </div>
        </div>

        <div class="p-4 mt-3 mb-3 overflow-hidden border shadow rounded-4 submanagment">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('admin.plans.update', $plan) }}" method="post">
                        @csrf
                        @method('PUT')

                        @include('admin.plans.form', ['plan' => $plan])

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Save & Publish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin.app>