<x-layouts.frontend.internal>

    @push('styles')
        <style>
            .container {
                background: initial !important;
                box-shadow: initial !important;
            }
        </style>
    @endpush

    <div class="container">
        <h1>Pricing Plans</h1>

        @foreach ($plans as $plan)

            <div class="plan-card card">
                <h3>{{ $plan->name }} - ${{ $plan->price }}/month</h3>
                <p>✅ {{ $plan->book_number }} Books / Month</p>
                <p>✍️ Write Book with AI</p>
                <p>🔤 {{ $plan->word_number }} Word Limit</p>
                <p>🧱 Advanced Editor</p>

                <p class="highlight">✨ {{ $plan->description }}</p>

                <a class="btn btn-primary" href="#">Get Started</a>
            </div>

        @endforeach

        {{-- <div class="plan">
            <h3>🔹 Basic Plan – $21/month</h3>
            <p>✅ 10 Books / Month</p>
            <p>⚡ One-Click Full Book Generator</p>
            <p>✍️ Write Book with AI</p>
            <p>🎨 Custom Book Creation</p>
            <p>♾️ 300k Words</p>
            <p>🧰 Full Text Editor</p>
            <p>📤 Export in PDF & Word</p>
            <p>💬 Customer Support</p>
            <p class="highlight">✨ Ideal for active writers!</p>
            <a class="button" href="/subscribe?plan=basic">Subscribe Now</a>
        </div>

        <div class="plan">
            <h3>🔹 Pro Plan – $41/month</h3>
            <p>✅ 25 Books / Month</p>
            <p>⚡ One-Click Full Book Generator</p>
            <p>✍️ Write Book with AI</p>
            <p>🎨 Custom Book Creation</p>
            <p>♾️ <b>Unlimited Words</b></p>
            <p>🧰 Full Text Editor</p>
            <p>📤 Export in PDF & Word</p>
            <p>🥇 Priority Customer Support</p>
            <p>🤖 AI-Assisted Writing</p>
            <p>☁️ Cloud Storage</p>

            <p class="highlight">✨ For professionals creating at scale!</p>
            <a class="button" href="/subscribe?plan=pro">Subscribe Now</a>
        </div>

        <div class="plan">
            <h3>🔥 Lifetime Plan – $299 (One-Time)</h3>
            <p>⚡ One-Click Full Book Generator</p>
            <p>♾️ Unlimited Books & Words</p>
            <p>♾️ Unlimited Words</p>
            <p>📤 Export in PDF & Word</p>
            <p>☁️ Unlimited Cloud Storage</p>
            <p>💎 Exclusive VIP Support</p>
            <p>👤 Limited to First 100 Users</p>
            <p>🏆 Enjoy VIP access to everything — with no limits</p>
            <p class="highlight">✨ Lifetime Access – No Recurring Fees</p>
            <a class="button" href="/subscribe?plan=lifetime">Get Lifetime Access</a>
        </div> --}}

        <p class="footer">
            Thank you for trusting Book Render – where your privacy is protected, and your books are born.
        </p>
    </div>
</x-layouts.frontend.internal>