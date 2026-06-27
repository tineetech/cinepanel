<x-guest-layout>
    <div class="auth-form-header">
        <h1>Selamat Datang</h1>
        <p>Masuk ke akun Anda untuk melanjutkan</p>
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <div class="form-input-wrapper">
                <i class="fa-solid fa-envelope"></i>
                <input id="email" class="form-input" type="email" name="email" :value="old('email')" placeholder="admin@gmail.com" required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="input-error" />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="form-input-wrapper">
                <i class="fa-solid fa-lock"></i>
                <input id="password" class="form-input" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                <i class="fa-solid fa-eye" id="togglePassword" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--text-3);cursor:pointer;font-size:14px;z-index:2"></i>
            </div>
            <x-input-error :messages="$errors->get('password')" class="input-error" />
        </div>

        <div class="form-check">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Ingat saya</label>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk
        </button>
    </form>
</x-guest-layout>
