const wrapper = document.querySelector('.wrapper');
const loginForm = document.querySelector('.form-box.login');
const registerForm = document.querySelector('.form-box.register');
const showRegister = document.getElementById('show-register');
const showLogin = document.getElementById('show-login');

showRegister.addEventListener('click', () => {
    wrapper.classList.add('active');
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
});

showLogin.addEventListener('click', () => {
    wrapper.classList.remove('active');
    registerForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
});
