<div>
    <!-- First Name -->
    <div class="mb-3 w-75">
        <label for="fname" class="form-label">First Name</label>
        <input type="text" name="fname" class="form-control @error('fname') is-invalid @enderror" id="fname"
            placeholder="Enter First Name" value="{{ old('fname', $user?->fname) }}">
        @error('fname')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Last Name -->
    <div class="mb-3 w-75">
        <label for="lname" class="form-label">Last Name</label>
        <input type="text" name="lname" class="form-control @error('lname') is-invalid @enderror" id="lname"
            placeholder="Enter Last Name" value="{{ old('lname', $user?->lname) }}">
        @error('lname')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-3 w-75">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
            placeholder="Enter Email" value="{{ old('email', $user?->email) }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3 w-75">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            id="password" placeholder="Enter Password">

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Role Selection -->
    <div class="mb-3 w-75">
        <label for="role" class="form-label">User Role</label>
        <select name="role" class="form-select" id="role">
            <option value="user" @selected($user?->id ? $user?->role == old('role', 'user') : true)>User</option>
            <option value="admin" @selected($user?->role == old('role', 'admin'))>Admin</option>
        </select>

        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Subscription Type -->
    <div class="mb-3 w-75">
        <label for="subscription" class="form-label">Subscription Plan</label>

        <select name="subscription" class="form-select" id="subscription">

            <option value="" selected disabled>Choose subscription</option>

            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" @selected($plan->id == old('subscription', $userSubscription?->plan_id ?? $freePlan->id))>
                    {{ $plan->name }}
                </option>
            @endforeach
        </select>

        <p class="font-main small w-75">
            The free trial represents the amount of time before charging the first recurring
            payment.
            The sign-up fee applies regardless of the free trial.
        </p>

        @error('subscription')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>