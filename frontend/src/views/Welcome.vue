<script setup lang="ts">

</script>
<template>
    <div class="
    mt-[100px] m-auto w-full max-w-3xl p-6
    flex justify-content align-center" >
        <div class="w-1/2 p-[10px] bg-neutral-700">
            <form>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-300">Acessar Conta</h2>
                    <input
                        id="login-email"
                        type="email"
                        placeholder="Insira o seu E-mail"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <input
                        id="login-password"
                        type="password"
                        placeholder="Insira a sua Senha"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                </div>
                <button class="mt-2 cursor-pointer bg-lime-800 hover:bg-lime-900 text-white font-bold py-2 px-4 border border-lime-700 rounded">
                    Acessar
                </button>
            </form>
        </div>
        <div class=" w-1/2 p-[10px]">
            <form>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-300">Criar Conta</h2>
                    <input
                        id="register-nome"
                        name="register-nome"
                        type="email"
                        placeholder="Insira o seu Nome"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <input
                        id="register-email"
                        name="register-email"
                        type="email"
                        placeholder="Insira o seu E-mail"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <input
                        id="register-password"
                        name="register-password"
                        type="password"
                        placeholder="Insira a sua Senha"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <button class="mt-2 cursor-pointer bg-lime-800 hover:bg-lime-900 text-white font-bold py-2 px-4 border border-lime-700 rounded">
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
