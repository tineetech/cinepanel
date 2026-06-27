<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <div class="form-input-wrapper">
                <i class="fa-solid fa-user"></i>
                <input id="name" class="form-input" type="text" name="name" :value="old('name')" placeholder="Nama Anda" required autofocus autocomplete="name">
            </div>
            <x-input-error :messages="$errors->get('name')" class="input-error" />
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="form-input-wrapper">
                <i class="fa-solid fa-envelope"></i>
                <input id="email" class="form-input" type="email" name="email" :value="old('email')" placeholder="email@domain.com" required autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="input-error" />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="form-input-wrapper">
                <i class="fa-solid fa-lock"></i>
                <input id="password" class="form-input" type="password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="input-error" />
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="form-input-wrapper">
                <i class="fa-solid fa-lock"></i>
                <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="input-error" />
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-user-plus"></i> Daftar
        </button>

        <div class="auth-footer" style="margin-top:16px">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>
    </form>
</x-guest-layout>
