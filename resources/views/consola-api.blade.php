<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Consola API</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Autenticación --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-1">1. Autenticación (obtener token)</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Se envía <code>POST /api/login</code> y el token que devuelve se usa como
                    <code>Authorization: Bearer ...</code> en las siguientes peticiones.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo</label>
                        <input id="email" type="email" autocomplete="username"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input id="password" type="password" autocomplete="current-password"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <button id="btnLogin" type="button"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest">
                        Obtener token
                    </button>
                    <button id="btnLogout" type="button"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest disabled:opacity-40">
                        Revocar token (logout API)
                    </button>
                    <span id="estadoToken" class="text-sm text-gray-600"></span>
                </div>
            </div>

            {{-- 2. Petición --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-4">2. Armar y enviar una petición</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="recurso" class="block text-sm font-medium text-gray-700">Recurso</label>
                        <select id="recurso" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="sedes">sedes</option>
                            <option value="atracciones">atracciones</option>
                        </select>
                    </div>
                    <div>
                        <label for="metodo" class="block text-sm font-medium text-gray-700">Método HTTP</label>
                        <select id="metodo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option>GET</option>
                            <option>POST</option>
                            <option>PUT</option>
                            <option>DELETE</option>
                        </select>
                    </div>
                    <div id="idWrap">
                        <label for="idRegistro" class="block text-sm font-medium text-gray-700">ID del registro</label>
                        <input id="idRegistro" type="text" placeholder="vacío = listar todos"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div id="bodyWrap" class="mt-4">
                    <label for="cuerpo" class="block text-sm font-medium text-gray-700">Cuerpo (JSON)</label>
                    <textarea id="cuerpo" rows="6"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm font-mono text-sm"></textarea>
                    <p id="ayudaCuerpo" class="mt-1 text-xs text-gray-500"></p>
                </div>

                <div class="mt-4">
                    <button id="btnEnviar" type="button"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest">
                        Enviar petición
                    </button>
                </div>

                <div id="listaWrap" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Haz clic en un registro para usar su ID:</p>
                    <div id="lista" class="flex flex-wrap gap-2"></div>
                </div>
            </div>

            {{-- 3. Resultado --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-4">3. Petición enviada y respuesta</h3>

                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Petición</p>
                <pre id="verPeticion" class="bg-gray-100 text-gray-800 p-4 rounded overflow-x-auto text-xs mb-4">Aún no has enviado ninguna petición.</pre>

                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                    Respuesta <span id="verEstado" class="ml-2 normal-case"></span>
                </p>
                <pre id="verRespuesta" class="bg-gray-900 text-green-300 p-4 rounded overflow-x-auto text-xs"></pre>
            </div>

        </div>
    </div>

    <script>
        const API = @json(url('/api'));
        const $ = (id) => document.getElementById(id);
        let token = sessionStorage.getItem('api_token') || '';

        const plantillas = {
            sedes: { nombre: 'Sede demo', ubicacion: 'Calle 1 # 2-3' },
            atracciones: {
                nombre: 'Montaña rusa',
                edad_minima: 10,
                estatura_minima: 1.2,
                id_sede: 'PEGA-AQUI-EL-ID-DE-UNA-SEDE'
            }
        };

        function pintarToken() {
            $('estadoToken').textContent = token
                ? 'Token activo: ' + token.slice(0, 10) + '…'
                : 'Sin token (no autenticado)';
            $('btnLogout').disabled = !token;
        }

        function pintarFormulario() {
            const metodo = $('metodo').value;
            const recurso = $('recurso').value;
            const lleva = metodo === 'POST' || metodo === 'PUT';

            $('idWrap').style.display = metodo === 'POST' ? 'none' : '';
            $('bodyWrap').style.display = lleva ? '' : 'none';

            if (lleva) {
                $('cuerpo').value = JSON.stringify(plantillas[recurso], null, 2);
                $('ayudaCuerpo').textContent = recurso === 'atracciones'
                    ? 'Reemplaza id_sede por el ID de una sede real (haz un GET a sedes y haz clic en una).'
                    : '';
            }
        }

        async function llamar(metodo, url, cuerpo, autenticar = true) {
            const headers = { 'Accept': 'application/json' };
            if (cuerpo !== undefined) headers['Content-Type'] = 'application/json';
            if (autenticar && token) headers['Authorization'] = 'Bearer ' + token;

            const opciones = { method: metodo, headers };
            if (cuerpo !== undefined) opciones.body = JSON.stringify(cuerpo);

            const resp = await fetch(url, opciones);
            const texto = await resp.text();
            let datos;
            try { datos = JSON.parse(texto); } catch (e) { datos = texto; }

            return { resp, datos, headers };
        }

        function mostrar(metodo, url, headers, cuerpo, resp, datos) {
            const visibles = { ...headers };
            if (visibles['Authorization']) {
                visibles['Authorization'] = 'Bearer ' + token.slice(0, 8) + '…(oculto)';
            }

            let texto = metodo + ' ' + url + '\n\nCabeceras:\n' + JSON.stringify(visibles, null, 2);
            if (cuerpo !== undefined) {
                texto += '\n\nCuerpo:\n' + JSON.stringify(cuerpo, null, 2);
            }
            $('verPeticion').textContent = texto;

            $('verEstado').textContent = 'HTTP ' + resp.status + ' ' + resp.statusText;
            $('verEstado').className = 'ml-2 normal-case ' + (resp.ok ? 'text-green-600' : 'text-red-600');
            $('verRespuesta').textContent = typeof datos === 'string'
                ? datos
                : JSON.stringify(datos, null, 2);
        }

        function pintarLista(recurso, datos) {
            const cont = $('lista');
            cont.innerHTML = '';

            if (!Array.isArray(datos) || datos.length === 0) {
                $('listaWrap').classList.add('hidden');
                return;
            }

            const clave = recurso === 'sedes' ? 'id_sede' : 'id_atraccion';
            datos.forEach((item) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = item.nombre;
                b.className = 'px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200';
                b.addEventListener('click', () => { $('idRegistro').value = item[clave]; });
                cont.appendChild(b);
            });
            $('listaWrap').classList.remove('hidden');
        }

        $('btnLogin').addEventListener('click', async () => {
            const cuerpo = { email: $('email').value, password: $('password').value };
            const url = API + '/login';
            try {
                const { resp, datos, headers } = await llamar('POST', url, cuerpo, false);
                if (resp.ok && datos.token) {
                    token = datos.token;
                    sessionStorage.setItem('api_token', token);
                    $('password').value = '';
                }
                // En la vista de petición no mostramos la contraseña
                mostrar('POST', url, headers, { email: cuerpo.email, password: '********' }, resp, datos);
                pintarToken();
            } catch (e) {
                $('verRespuesta').textContent = 'No se pudo conectar con la API: ' + e.message;
            }
        });

        $('btnLogout').addEventListener('click', async () => {
            const url = API + '/logout';
            try {
                const { resp, datos, headers } = await llamar('POST', url);
                mostrar('POST', url, headers, undefined, resp, datos);
            } catch (e) {
                $('verRespuesta').textContent = 'No se pudo conectar con la API: ' + e.message;
            }
            token = '';
            sessionStorage.removeItem('api_token');
            pintarToken();
        });

        $('btnEnviar').addEventListener('click', async () => {
            const recurso = $('recurso').value;
            const metodo = $('metodo').value;
            const id = $('idRegistro').value.trim();

            if ((metodo === 'PUT' || metodo === 'DELETE') && !id) {
                $('verRespuesta').textContent = 'Para ' + metodo + ' necesitas indicar el ID del registro.';
                return;
            }

            let url = API + '/' + recurso;
            if (metodo !== 'POST' && id) url += '/' + encodeURIComponent(id);

            let cuerpo;
            if (metodo === 'POST' || metodo === 'PUT') {
                try {
                    cuerpo = JSON.parse($('cuerpo').value);
                } catch (e) {
                    $('verRespuesta').textContent = 'El cuerpo no es un JSON válido: ' + e.message;
                    return;
                }
            }

            try {
                const { resp, datos, headers } = await llamar(metodo, url, cuerpo);
                mostrar(metodo, url, headers, cuerpo, resp, datos);
                if (metodo === 'GET' && !id) pintarLista(recurso, datos);
            } catch (e) {
                $('verRespuesta').textContent = 'No se pudo conectar con la API: ' + e.message;
            }
        });

        $('metodo').addEventListener('change', pintarFormulario);
        $('recurso').addEventListener('change', () => {
            $('idRegistro').value = '';
            $('listaWrap').classList.add('hidden');
            pintarFormulario();
        });

        pintarToken();
        pintarFormulario();
    </script>
</x-app-layout>
