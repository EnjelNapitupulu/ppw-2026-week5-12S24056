<x-layout title="Login Pustakawan">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-3">Login Pustakawan</h4>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.attempt') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', request()->cookie('del_last_user_email')) }}"
                                   class="form-control @error('email') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button class="btn btn-del w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>