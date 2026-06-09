<div>
    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center">
        <div class="w-100">
            <!-- Sign In Section -->
            <div class="bg-white">
                <div class="content content-full">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                            <!-- Header -->
                            <div class="text-center">
                                <p class="mb-2">
                                    <i class="fa fa-2x fa-circle-notch text-primary"></i>
                                </p>
                                <h1 class="h4 mb-1">Se connecter</h1>
                                @error('loginFailed')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                                <h2 class="h6 font-w400 text-muted mb-3">
                                    Bienvenue sur RESTO, veuillez vous connecter
                                </h2>
                            </div>
                            <!-- END Header -->

                            <!-- Sign In Form -->
                            <form wire:submit="login">
                                <div class="py-3">
                                    <div class="form-group">
                                        <input wire:model="email" type="text"
                                            class="form-control form-control-lg form-control-alt"
                                            placeholder="Votre email">
                                        @error('email') <div class="text-danger font-size-sm">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group">
                                        <input wire:model="password" type="password"
                                            class="form-control form-control-lg form-control-alt"
                                            placeholder="Mot de passe">
                                        @error('password') <div class="text-danger font-size-sm">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="form-group row flex justify-content-center m-0">
                                    <div wire:loading class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Chargement...</span>
                                    </div>
                                </div>
                                <div class="form-group row flex justify-content-center mb-0">
                                    <div class="col-md-6 col-xl-5">
                                        <button type="submit" class="btn btn-block btn-primary">
                                            <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Se connecter
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- END Sign In Form -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Sign In Section -->

            <!-- Footer -->
            <div class="font-size-sm text-center text-muted py-3">
                <strong>RESTO</strong> &copy; {{ date('Y') }}
            </div>
            <!-- END Footer -->
        </div>
    </div>
</div>
