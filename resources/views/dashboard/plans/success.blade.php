<x-layouts.dashboard.app>
    @push('styles')
        <style>
            .success-message {
                background-color: #28a745;
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
        <h1 class="text-center mb-4">تمت العملية بنجاح!</h1>
        <div class="success-message">
            <p>تم اشتراكك في الخطة بنجاح! شكراً لك على الدفع. يمكنك الآن الاستمتاع بخدماتك.</p>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-success">الرجوع إلى الصفحة الرئيسية</a>
        </div>
    </div>
</x-layouts.dashboard.app>