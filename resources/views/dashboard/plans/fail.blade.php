<x-layouts.dashboard.app>
    @push('styles')
        <style>
            .error-message {
                background-color: #dc3545;
                color: white;
                padding: 20px;
                font-size: 18px;
                font-weight: bold;
                border-radius: 5px;
                text-align: center;
                margin-top: 20px;
            }
        </style>
    @endpush

    <div class="container py-5">
        <h1 class="text-center mb-4">فشلت العملية!</h1>
        <div class="error-message">
            <p>حدث خطأ أثناء عملية الدفع. من فضلك، حاول مرة أخرى في وقت لاحق.</p>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('dashboard.plans.index') }}" class="btn btn-danger">محاولة الدفع مرة أخرى</a>
        </div>
    </div>
</x-layouts.dashboard.app>