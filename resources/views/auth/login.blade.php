@extends('layouts.app')

@section('content')
<?php
$hasErrors = $errors->any();

$isDirect = request('direct') === 'true';
$showLoginDirectly = $hasErrors || $isDirect;
?>

{{-- Landing Page --}}
<div class="landing-page" id="landingPage" style="display: {{ $showLoginDirectly ? 'none' : 'flex' }};">
    <div class="landing-content">
        <div class="landing-wrapper">
            <div class="logo-section">
                <img src="{{asset('images/logo_sa_labas.png')}}" alt="GazLite Logo" class="main-logo-img">
            </div>

            <div class="illustration-section">
                <img src="{{asset('images/human.png')}}" alt="Easy Management Illustration" class="human-illustration">
            </div>

            <div class="tagline">
                <p>Easy Management for your Store.</p>
            </div>

            <div class="progress-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
            </div>

            <button class="landing-signin-button" onclick="showLoginForm()">
                Sign in
            </button>
        </div>
    </div>
</div>

{{-- Login Page --}}
<div class="login-page" id="loginFormPage" style="display: {{ $showLoginDirectly ? 'flex' : 'none' }};">
    <div class="login-header">
        <div class="header-left">
            <button type="button" class="back-btn" onclick="showLandingPage()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <h1 class="page-title">Sign in</h1>
        </div>
        <div class="logo-container">
            <img src="{{asset('images/logo_nya.png')}}" alt="Logo" class="header-logo">
        </div>
    </div>

    <div class="login-content">
        <div class="form-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="email" class="input-label">Email or Phone Number</label>
                    <input 
                        id="email" 
                        type="text" 
                        class="form-input{{ $errors->has('email') ? ' error' : '' }}" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Email or Phone Number"
                        required 
                        autofocus
                    >
                    @if($errors->has('email'))
                        <div class="error-text">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="input-group">
                    <label class="input-label" for="password">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        class="form-input{{ $errors->has('password') ? ' error' : '' }}" 
                        placeholder="At least 8 characters" 
                        name="password" 
                        required
                    >
                    @if($errors->has('password'))
                        <div class="error-text">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button class="signin-button" type="submit">
                    Sign in
                </button>

                <div class="forgot-section">
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot password?
                    </a>
                </div>

                <div class="progress-dots">
                    <span class="dot"></span>
                    <span class="dot active"></span>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #ffffff;
}

.landing-page {
    min-height: 100vh;
    background-color: #ffffff;
    display: flex;
    flex-direction: column;
}

.landing-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 24px;
}

.landing-wrapper {
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.logo-section {
    margin-bottom: 40px;
}

.main-logo-img {
    max-width: 549px;
    width: 100%;
    max-height: 186px;
    height: 100%;
}

.illustration-section {
    margin-bottom: 30px;
}

.human-illustration {
    max-width: 256.42px;
    width: 100%;
    max-height: 284px;
    height: auto;
}

.tagline {
    margin-bottom: 30px;
}

.tagline p {
    font-size: 18px;
    color: #666666;
    font-weight: 400;
}

.progress-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 40px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #E0E0E0;
    transition: background-color 0.3s ease;
}

.dot.active {
    /* background-color: #5DADE2; */
    background-color: #e3342f;
}

.landing-signin-button {
    max-width: 327px;
    width: 100%;
    height: 57px;
    border: none;
    border-radius: 28px;
    background-color: #5DADE2;
    color: white;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    margin-bottom: 24px;
    transition: all 0.3s ease;
}

.landing-signin-button:hover {
    transform: translateY(-2px); 
    background-color: #3498DB;
}

.role-selection-page {
    min-height: 100vh;
    background-color: #ffffff;
    display: flex;
    flex-direction: column;
}

.role-header {
    display: flex;
    align-items: center;
    padding: 20px 24px;
    background-color: #ffffff;
}

.role-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 24px;
    margin-top: -80px;
}

.role-wrapper {
    width: 100%;
    max-width: 350px;
    text-align: center;
}

.welcome-section {
    margin-bottom: 20px;
}

.welcome-title {
    font-size: 24px;
    color: #4A5568;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.2;
}

.welcome-subtitle {
    font-size: 14px;
    color: #666666;
    font-weight: 400;
}

.context-image-section {
    margin-bottom: 25px;
}

.context-image {
    max-width: 200px;
    width: 100%;
    height: auto;
}

.role-selection-page .progress-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 25px;
}

.role-buttons {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.role-button {
    width: 100%;
    height: 48px;
    border: none;
    border-radius: 24px;
    background-color: #5DADE2;
    color: white;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    position: relative;
}

.role-button:hover {
    background-color: #3498DB;
    transform: translateY(-2px);
}

/* New styles for role selection indication */
.role-button.selected {
    opacity: 0.8;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #5DADE2;
}

.selected-indicator {
    position: absolute;
    right: 15px;
    font-size: 16px;
    font-weight: bold;
}

.role-icon {
    width: 20px;
    height: 20px;
    stroke: white;
}

/* Continue button styles */
.continue-section {
    margin-bottom: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.continue-section.show {
    opacity: 1;
}

.continue-button {
    width: 100%;
    height: 48px;
    border: none;
    border-radius: 24px;
    background-color: #5DADE2;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.continue-button:hover {
    background-color: #3498DB;
    transform: translateY(-2px);
}

.create-account-section {
    margin-bottom: 15px;
}

.create-account-link {
    color: #5DADE2;
    text-decoration: underline;
    font-size: 14px;
    font-weight: 400;
}

.create-account-link:hover {
    color: #3498DB;
}

.login-page {
    min-height: 100vh;
    background-color: #ffffff;
    display: flex;
    flex-direction: column;
}

.login-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    background-color: #ffffff;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.back-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background-color: #5DADE2;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
    z-index: 10;
    position: relative;
}

.back-btn:hover {
    background-color: #3498DB;
}

.page-title {
    margin-top: 7px;
    margin-left: 20px;
    font-size: 27px;
    font-weight: 550;
    color: #5DADE2; 
    letter-spacing: 1px;
}

.logo-container {
    width: 60px;
    height: 60px;
}

.header-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.login-content {
    margin-top: -57px;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 24px;
}

.form-container {
    width: 100%;
    max-width: 400px;
}

/* Role indicator in login form */
.role-indicator {
    background-color: #F0F8FF;
    border-left: 4px solid #5DADE2;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.role-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.role-label {
    font-size: 14px;
    color: #666666;
}

.role-name {
    font-size: 16px;
    font-weight: 600;
    color: #5DADE2;
}

.input-group {
    margin-bottom: 24px;
}

.input-label {
    display: block;
    font-size: 16px;
    color: #666666;
    margin-bottom: 8px;
    font-weight: 400;
}

.form-input {
    width: 100%;
    height: 56px;
    padding: 0 20px;
    border: none;
    border-radius: 12px !important;
    background-color: #F5F5F5;
    font-size: 16px;
    color: #333333;
    outline: none;
    transition: background-color 0.2s ease;
}

.form-input::placeholder {
    color: #AAAAAA;
    font-size: 16px;
}

.form-input:focus {
    background-color: #EEEEEE;
}

.form-input.error {
    background-color: #FFE5E5;
    border: 1px solid #FF6B6B;
}

.error-text {
    color: #FF6B6B;
    font-size: 14px;
    margin-top: 6px;
}

.otp-section {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 32px;
}

.otp-text {
    font-size: 16px;
    color: #666666;
}

.email-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    background-color: #5DADE2;
    color: white;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.email-btn:hover {
    background-color: #3498DB;
}

.email-icon {
    width: 16px;
    height: 16px;
    stroke: white;
    fill: none;
}

.signin-button {
    width: 100%;
    height: 56px;
    border: none;
    border-radius: 12px;
    background-color: #5DADE2; 
    color: white;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    margin-bottom: 24px;
    transition: background-color 0.2s ease;
}

.signin-button:hover {
    background-color: #3498DB;
}

.forgot-section {
    text-align: center;
    margin-bottom: 24px;
}

.forgot-link {
    color: #5DADE2; 
    text-decoration: none;
    font-size: 16px;
    font-weight: 400;
}

.forgot-link:hover {
    text-decoration: underline;
}

.alert-error {
    background-color: #FFE5E5;
    color: #FF6B6B;
    padding: 12px 16px;
    border-radius: 8px;
    border-left: 4px solid #FF6B6B;
    margin-bottom: 20px;
    font-size: 14px;
}

@media (max-width: 480px) {
    .login-header, .role-header {
        padding: 16px 20px;
    }
    
    .landing-content, .login-content, .role-content {
        padding: 0 20px;
    }
    
    .main-logo-img {
        max-width: 200px;
    }
    
    .human-illustration {
        max-width: 160px;
    }
    
    .context-image {
        max-width: 180px;
    }
    
    .tagline p {
        font-size: 16px;
    }
    
    .welcome-title {
        font-size: 22px;
    }
    
    .welcome-subtitle {
        font-size: 13px;
    }
    
    .header-left {
        gap: 12px;
    }
    
    .page-title {
        font-size: 18px;
    }
    
    .form-input, .signin-button {
        height: 52px;
    }
    
    .role-button {
        height: 44px;
        font-size: 15px;
    }
    
    .role-icon {
        width: 18px;
        height: 18px;
    }
    
    .role-content {
        margin-top: -60px;
    }
    
    .role-wrapper {
        max-width: 320px;
    }
}
</style>

<script>
function showLoginForm() {
    document.getElementById('landingPage').style.display = 'none';
    document.getElementById('loginFormPage').style.display = 'flex';
}

function showLandingPage() {
    document.getElementById('loginFormPage').style.display = 'none';
    document.getElementById('landingPage').style.display = 'flex';
}
</script>
@endsection