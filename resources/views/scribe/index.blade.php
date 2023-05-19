<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Telegram Notify Documentation</title>
    <meta name="author" content="Miguel Alejandro González Antúnez">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-elements.style.css") }}" media="screen">

    <script src="http://telgmnotify.qtokesoft.cu/js/lodash.min.js"></script>

    <link rel="stylesheet" href="http://telgmnotify.qtokesoft.cu/css/docco.min.css">
    <script src="http://telgmnotify.qtokesoft.cu/js/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
    <script type="module">
        import {
            CodeJar
        } from "http://telgmnotify.qtokesoft.cu/js/codejar.js"
        window.CodeJar = CodeJar;
    </script>

        <script>
        var tryItOutBaseUrl = "http://telgmnotify.qtokesoft.cu";
        var useCsrf = Boolean("");
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-4.19.1.js") }}"></script>
    <style>
        .code-editor,
        .response-content {
            color: whitesmoke;
            background-color: transparent;
        }

        /*
             Problem: we want syntax highlighting for the Try It Out JSON body code editor
             However, the Try It Out area uses a dark background, while request and response samples
             (which are already highlighted) use a light background. HighlightJS can only use one theme per document.
             Our options:
             1. Change the bg of one. => No, it looks out of place on the page.
             2. Use the same highlighting for both. => Nope, one would be unreadable.
             3. Copy styles for a dark-bg h1js theme and prefix them for the CodeEditor, which is what we're doing.
             Since it's only JSON, we only need a few styles anyway.
             Styles taken from the Nord theme: https://github.com/highlightjs/highlight.js/blob/3997c9b430a568d5ad46d96693b90a74fc01ea7f/src/styles/nord.css#L2
             */
        .code-editor>.hljs-attr {
            color: #8FBCBB;
        }

        .code-editor>.hljs-string {
            color: #A3BE8C;
        }

        .code-editor>.hljs-number {
            color: #B48EAD;
        }

        .code-editor>.hljs-literal {
            color: #81A1C1;
        }

        /* pt-sans-regular - latin */
        @font-face {
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-display: swap;
            font-family: 'PT Sans';
            font-style: normal;
            font-weight: 400;
            src:
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("http://telgmnotify.qtokesoft.cu/fonts/pt-sans-v17-latin-regular.woff2") format('woff2'),
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
            url("http://telgmnotify.qtokesoft.cu/fonts/pt-sans-v17-latin-regular.woff") format('woff');
        }
    </style>

    <script>
        function tryItOut(btnElement) {
            btnElement.disabled = true;

            let endpointId = btnElement.dataset.endpoint;

            let errorPanel = document.querySelector(`.tryItOut-error[data-endpoint=${endpointId}]`);
            errorPanel.hidden = true;
            let responsePanel = document.querySelector(`.tryItOut-response[data-endpoint=${endpointId}]`);
            responsePanel.hidden = true;

            let form = btnElement.form;
            let {
                method,
                path,
                hasjsonbody: hasJsonBody
            } = form.dataset;
            let body = {};
            if (hasJsonBody === "1") {
                body = form.querySelector('.code-editor').textContent;
            } else if (form.dataset.hasfiles === "1") {
                body = new FormData();
                form.querySelectorAll('input[data-component=body]')
                    .forEach(el => {
                        if (el.type === 'file') {
                            if (el.files[0]) body.append(el.name, el.files[0])
                        } else body.append(el.name, el.value);
                    });
            } else {
                form.querySelectorAll('input[data-component=body]').forEach(el => {
                    _.set(body, el.name, el.value);
                });
            }

            const urlParameters = form.querySelectorAll('input[data-component=url]');
            urlParameters.forEach(el => (path = path.replace(new RegExp(`\\{${el.name}\\??}`), el.value)));

            const headers = Object.fromEntries(Array.from(form.querySelectorAll('input[data-component=header]'))
                .map(el => [el.name, (el.dataset.prefix || '') + el.value]));

            const query = {}
            form.querySelectorAll('input[data-component=query]').forEach(el => {
                _.set(query, el.name, el.value);
            });

            let preflightPromise = Promise.resolve();
            if (window.useCsrf && window.csrfUrl) {
                preflightPromise = makeAPICall('GET', window.csrfUrl).then(() => {
                    headers['X-XSRF-TOKEN'] = getCookie('XSRF-TOKEN');
                });
            }

            return preflightPromise.then(() => makeAPICall(method, path, body, query, headers, endpointId))
                .then(([responseStatus, statusText, responseContent, responseHeaders]) => {
                    responsePanel.hidden = false;
                    responsePanel.querySelector(`.response-status`).textContent = responseStatus + " " + statusText;

                    let contentEl = responsePanel.querySelector(`.response-content`);
                    if (responseContent === '') {
                        contentEl.textContent = contentEl.dataset.emptyResponseText;
                        return;
                    }

                    // Prettify it if it's JSON
                    let isJson = false;
                    try {
                        const jsonParsed = JSON.parse(responseContent);
                        if (jsonParsed !== null) {
                            isJson = true;
                            responseContent = JSON.stringify(jsonParsed, null, 4);
                        }
                    } catch (e) {}

                    contentEl.innerHTML = responseContent;
                    isJson && window.hljs.highlightElement(contentEl);
                })
                .catch(err => {
                    console.log(err);
                    let errorMessage = err.message || err;
                    errorPanel.hidden = false;
                    errorPanel.querySelector(`.error-message`).textContent = errorMessage;
                })
                .finally(() => {
                    btnElement.disabled = false
                });
        }

        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.tryItOut-btn').forEach(el => {
                el.addEventListener('click', () => tryItOut(el));
            });
        })
    </script>
    
</head>

<body>

        <script>
        function switchExampleLanguage(lang) {
            document.querySelectorAll(`.example-request`).forEach(el => el.style.display = 'none');
            document.querySelectorAll(`.example-request-${lang}`).forEach(el => el.style.display = 'initial');
            document.querySelectorAll(`.example-request-lang-toggle`).forEach(el => el.value = lang);
        }
    </script>
    
    <script>
        function switchExampleResponse(endpointId, index) {
            document.querySelectorAll(`.example-response-${endpointId}`).forEach(el => el.style.display = 'none');
            document.querySelectorAll(`.example-response-${endpointId}-${index}`).forEach(el => el.style.display = 'initial');
            document.querySelectorAll(`.example-response-${endpointId}-toggle`).forEach(el => el.value = index);
        }


        /*
         * Requirement: a div with class `expansion-chevrons`
         *   (or `expansion-chevrons-solid` to use the solid version).
         * Also add the `expanded` class if your div is expanded by default.
         */
        function toggleExpansionChevrons(evt) {
            let elem = evt.currentTarget;

            let chevronsArea = elem.querySelector('.expansion-chevrons');
            const solid = chevronsArea.classList.contains('expansion-chevrons-solid');
            const newState = chevronsArea.classList.contains('expanded') ? 'expand' : 'expanded';
            if (newState === 'expanded') {
                const selector = solid ? '#expanded-chevron-solid' : '#expanded-chevron';
                const template = document.querySelector(selector);
                const chevron = template.content.cloneNode(true);
                chevronsArea.replaceChildren(chevron);
                chevronsArea.classList.add('expanded');
            } else {
                const selector = solid ? '#expand-chevron-solid' : '#expand-chevron';
                const template = document.querySelector(selector);
                const chevron = template.content.cloneNode(true);
                chevronsArea.replaceChildren(chevron);
                chevronsArea.classList.remove('expanded');
            }

        }

        /**
         * 1. Make sure the children are inside the parent element
         * 2. Add `expandable` class to the parent
         * 3. Add `children` class to the children.
         * 4. Wrap the default chevron SVG in a div with class `expansion-chevrons`
         *   (or `expansion-chevrons-solid` to use the solid version).
         *   Also add the `expanded` class if your div is expanded by default.
         */
        function toggleElementChildren(evt) {
            let elem = evt.currentTarget;
            let children = elem.querySelector(`.children`);
            if (!children) return;

            if (children.contains(event.target)) return;

            let oldState = children.style.display
            if (oldState === 'none') {
                children.style.removeProperty('display');
                toggleExpansionChevrons(evt);
            } else {
                children.style.display = 'none';
                toggleExpansionChevrons(evt);
            }

            evt.stopPropagation();
        }

        function highlightSidebarItem(evt = null) {
            if (evt && evt.oldURL) {
                let oldHash = new URL(evt.oldURL).hash.slice(1);
                if (oldHash) {
                    let previousItem = window['sidebar'].querySelector(`#toc-item-${oldHash}`);
                    previousItem.classList.remove('sl-bg-primary-tint');
                    previousItem.classList.add('sl-bg-canvas-100');
                }
            }

            let newHash = location.hash.slice(1);
            if (newHash) {
                let item = window['sidebar'].querySelector(`#toc-item-${newHash}`);
                item.classList.remove('sl-bg-canvas-100');
                item.classList.add('sl-bg-primary-tint');
            }
        }

        addEventListener('DOMContentLoaded', () => {
            highlightSidebarItem();

            document.querySelectorAll('.code-editor').forEach(elem => CodeJar(elem, (editor) => {
                // highlight.js does not trim old tags,
                // which means highlighting doesn't update on type (only on paste)
                // See https://github.com/antonmedv/codejar/issues/18
                editor.textContent = editor.textContent
                return hljs.highlightElement(editor)
            }));

            document.querySelectorAll('.expandable').forEach(el => {
                el.addEventListener('click', toggleElementChildren);
            });

            document.querySelectorAll('details').forEach(el => {
                el.addEventListener('toggle', toggleExpansionChevrons);
            });
        });

        addEventListener('hashchange', highlightSidebarItem);
    </script>

    <div class="sl-elements sl-antialiased sl-h-full sl-text-base sl-font-ui sl-text-body sl-flex sl-inset-0">

        <div id="sidebar" class="sl-flex sl-overflow-y-auto sl-flex-col sl-sticky sl-inset-y-0 sl-pt-8 sl-bg-canvas-100 sl-border-r" style="width: calc((100% - 1800px) / 2 + 300px); padding-left: calc((100% - 1800px) / 2); min-width: 300px; max-height: 100vh">
    <div class="sl-flex sl-items-center sl-mb-5 sl-ml-4">
                <h4 class="sl-text-paragraph sl-leading-snug sl-font-prose sl-font-semibold sl-text-heading">
            Telegram Notify Documentation
        </h4>
    </div>

    <div class="sl-flex sl-overflow-y-auto sl-flex-col sl-flex-grow sl-flex-shrink">
        <div class="sl-overflow-y-auto sl-w-full sl-bg-canvas-100">
            <div class="sl-my-3">
                                <div class="expandable">
                    <div title="Introduction" id="toc-item-introduction" class="sl-flex sl-items-center sl-h-md sl-pr-4 sl-pl-4 sl-bg-canvas-100 hover:sl-bg-canvas-200 sl-cursor-pointer sl-select-none">
                        <a href="#introduction" class="sl-flex-1 sl-items-center sl-truncate sl-mr-1.5 sl-p-0">Introduction</a>
                                            </div>

                                    </div>
                                <div class="expandable">
                    <div title="Authenticating requests" id="toc-item-authenticating-requests" class="sl-flex sl-items-center sl-h-md sl-pr-4 sl-pl-4 sl-bg-canvas-100 hover:sl-bg-canvas-200 sl-cursor-pointer sl-select-none">
                        <a href="#authenticating-requests" class="sl-flex-1 sl-items-center sl-truncate sl-mr-1.5 sl-p-0">Authenticating requests</a>
                                            </div>

                                    </div>
                                <div class="expandable">
                    <div title="Publication" id="toc-item-publication" class="sl-flex sl-items-center sl-h-md sl-pr-4 sl-pl-4 sl-bg-canvas-100 hover:sl-bg-canvas-200 sl-cursor-pointer sl-select-none">
                        <a href="#publication" class="sl-flex-1 sl-items-center sl-truncate sl-mr-1.5 sl-p-0">Publication</a>
                                                <div class="sl-flex sl-items-center sl-text-xs expansion-chevrons">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-fw sl-icon sl-text-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path fill="currentColor" d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z"></path>
                            </svg>
                        </div>
                                            </div>

                                        <div class="children" style="display: none;">
                                                <div class="expandable">
                            <div class="sl-flex sl-items-center sl-h-md sl-pr-4 sl-pl-8 sl-bg-canvas-100 hover:sl-bg-canvas-200 sl-cursor-pointer sl-select-none" id="toc-item-publication-POSTapi-publication">
                                <div class="sl-flex-1 sl-items-center sl-truncate sl-mr-1.5 sl-p-0" title="Publish">
                                    <a class="ElementsTableOfContentsItem sl-block sl-no-underline" href="#publication-POSTapi-publication">
                                        Publish
                                    </a>
                                </div>
                                                            </div>

                                                    </div>
                                            </div>
                                    </div>
                            </div>

        </div>
        <div class="sl-flex sl-items-center sl-px-4 sl-py-3 sl-border-t">
            Last updated: May 19, 2023
        </div>

        <div class="sl-flex sl-items-center sl-px-4 sl-py-3 sl-border-t">
            <a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a>
        </div>
        <div class="sl-flex sl-items-center sl-px-4 sl-py-3">
            <a href="http://telgmnotify.qtokesoft.cu">Back to application</a>
        </div>
    </div>
</div>
        <div class="sl-overflow-y-auto sl-flex-1 sl-w-full sl-px-16 sl-bg-canvas sl-py-16" style="max-width: 1500px;">

            <div class="sl-mb-10">
                <div class="sl-mb-4">
                    <h1 class="sl-text-5xl sl-leading-tight sl-font-prose sl-font-semibold sl-text-heading">
                        Telegram Notify Documentation
                    </h1>
                                        <a title="Download Postman collection" class="sl-mx-1" href="{{ route("scribe.postman") }}" target="_blank">
                        <small>Postman collection →</small>
                    </a>
                                                            <a title="Download OpenAPI spec" class="sl-mx-1" href="{{ route("scribe.openapi") }}" target="_blank">
                        <small>OpenAPI spec →</small>
                    </a>
                                    </div>

                <div class="sl-prose sl-markdown-viewer sl-my-4">
                    <h1 id="introduction">Introduction</h1>
<p>API para la publicación de contenidos en Canales de Telegram</p>
<aside>
    <strong>Base URL</strong>: <code>http://telgmnotify.qtokesoft.cu</code>
</aside>
<p>Esta documentación tiene como objetivo proporcionar toda la información que necesita para trabajar con nuestra API.</p>
<aside>A medida que se desplace, verá ejemplos de código para trabajar con la API en diferentes lenguajes de programación en el área oscura a la derecha.
Puede cambiar el lenguaje de ejemplo utilizado en el campo de selección debajo del área.</aside>

                    <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_AUTH_KEY}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Puede obtener un token de acceso mediante el Endpoint <b>Login</b>.</p>
                </div>
            </div>

            <h1 id="publication"
        class="sl-text-5xl sl-leading-tight sl-font-prose sl-text-heading"
    >
        Publication
    </h1>

    <p>Endpoints para la publicación en Telegram</p>

                                <div class="sl-stack sl-stack--vertical sl-stack--8 HttpOperation sl-flex sl-flex-col sl-items-stretch sl-w-full">
    <div class="sl-stack sl-stack--vertical sl-stack--5 sl-flex sl-flex-col sl-items-stretch">
        <div class="sl-relative">
            <div class="sl-stack sl-stack--horizontal sl-stack--5 sl-flex sl-flex-row sl-items-center">
                <h2 class="sl-text-3xl sl-leading-tight sl-font-prose sl-text-heading sl-mt-5 sl-mb-1"
                    id="publication-POSTapi-publication">
                    Publish
                </h2>
            </div>
        </div>

        <div class="sl-relative">
            <div title="http://telgmnotify.qtokesoft.cu/api/publication"
                     class="sl-stack sl-stack--horizontal sl-stack--3 sl-inline-flex sl-flex-row sl-items-center sl-max-w-full sl-font-mono sl-py-2 sl-pr-4 sl-bg-canvas-50 sl-rounded-lg"
                >
                                            <div class="sl-text-lg sl-font-semibold sl-px-2.5 sl-py-1 sl-text-on-primary sl-rounded-lg"
                             style="background-color: black;"
                        >
                            POST
                        </div>
                                        <div class="sl-flex sl-overflow-x-hidden sl-text-lg sl-select-all">
                        <div dir="rtl"
                             class="sl-overflow-x-hidden sl-truncate sl-text-muted">http://telgmnotify.qtokesoft.cu</div>
                        <div class="sl-flex-1 sl-font-semibold">/api/publication</div>
                    </div>

                                                    <div class="sl-font-prose sl-font-semibold sl-px-1.5 sl-py-0.5 sl-text-on-primary sl-rounded-lg"
                                 style="background-color: darkred"
                            >requires authentication
                            </div>
                                    </div>
        </div>

        <p>Publica contenido en los canales asignados a sus Bots</p>
    </div>
    <div class="sl-flex">
        <div data-testid="two-column-left" class="sl-flex-1 sl-w-0">
            <div class="sl-stack sl-stack--vertical sl-stack--10 sl-flex sl-flex-col sl-items-stretch">
                <div class="sl-stack sl-stack--vertical sl-stack--8 sl-flex sl-flex-col sl-items-stretch">
                                            <div class="sl-stack sl-stack--vertical sl-stack--5 sl-flex sl-flex-col sl-items-stretch">
                            <h3 class="sl-text-2xl sl-leading-snug sl-font-prose">
                                Headers
                            </h3>
                            <div class="sl-text-sm">
                                                                    <div class="sl-flex sl-relative sl-max-w-full sl-py-2 sl-pl-3">
    <div class="sl-w-1 sl-mt-2 sl-mr-3 sl--ml-3 sl-border-t"></div>
    <div class="sl-stack sl-stack--vertical sl-stack--1 sl-flex sl-flex-1 sl-flex-col sl-items-stretch sl-max-w-full sl-ml-2 ">
        <div class="sl-flex sl-items-center sl-max-w-full">
                                        <div class="sl-flex sl-items-baseline sl-text-base">
                    <div class="sl-font-mono sl-font-semibold sl-mr-2">Authorization</div>
                                    </div>
                                    </div>
                                    <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-baseline sl-text-muted">
                <span>Example:</span> <!-- <span> important for spacing -->
                <div class="sl-flex sl-flex-1 sl-flex-wrap" style="gap: 4px;">
                    <div class="sl-max-w-full sl-break-all sl-px-1 sl-bg-canvas-tint sl-text-muted sl-rounded sl-border">
                        Bearer {YOUR_AUTH_KEY}
                    </div>
                </div>
            </div>
            </div>
</div>
                                                                    <div class="sl-flex sl-relative sl-max-w-full sl-py-2 sl-pl-3">
    <div class="sl-w-1 sl-mt-2 sl-mr-3 sl--ml-3 sl-border-t"></div>
    <div class="sl-stack sl-stack--vertical sl-stack--1 sl-flex sl-flex-1 sl-flex-col sl-items-stretch sl-max-w-full sl-ml-2 ">
        <div class="sl-flex sl-items-center sl-max-w-full">
                                        <div class="sl-flex sl-items-baseline sl-text-base">
                    <div class="sl-font-mono sl-font-semibold sl-mr-2">Content-Type</div>
                                    </div>
                                    </div>
                                    <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-baseline sl-text-muted">
                <span>Example:</span> <!-- <span> important for spacing -->
                <div class="sl-flex sl-flex-1 sl-flex-wrap" style="gap: 4px;">
                    <div class="sl-max-w-full sl-break-all sl-px-1 sl-bg-canvas-tint sl-text-muted sl-rounded sl-border">
                        multipart/form-data
                    </div>
                </div>
            </div>
            </div>
</div>
                                                                    <div class="sl-flex sl-relative sl-max-w-full sl-py-2 sl-pl-3">
    <div class="sl-w-1 sl-mt-2 sl-mr-3 sl--ml-3 sl-border-t"></div>
    <div class="sl-stack sl-stack--vertical sl-stack--1 sl-flex sl-flex-1 sl-flex-col sl-items-stretch sl-max-w-full sl-ml-2 ">
        <div class="sl-flex sl-items-center sl-max-w-full">
                                        <div class="sl-flex sl-items-baseline sl-text-base">
                    <div class="sl-font-mono sl-font-semibold sl-mr-2">Accept</div>
                                    </div>
                                    </div>
                                    <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-baseline sl-text-muted">
                <span>Example:</span> <!-- <span> important for spacing -->
                <div class="sl-flex sl-flex-1 sl-flex-wrap" style="gap: 4px;">
                    <div class="sl-max-w-full sl-break-all sl-px-1 sl-bg-canvas-tint sl-text-muted sl-rounded sl-border">
                        application/json
                    </div>
                </div>
            </div>
            </div>
</div>
                                                            </div>
                        </div>
                    
                    

                    
                                            <div class="sl-stack sl-stack--vertical sl-stack--6 sl-flex sl-flex-col sl-items-stretch">
                            <h3 class="sl-text-2xl sl-leading-snug sl-font-prose">Body Parameters</h3>

                                <div class="sl-text-sm">
                                    <div class="expandable sl-text-sm sl-border-l sl-ml-px">
        <div class="sl-flex sl-relative sl-max-w-full sl-py-2 sl-pl-3">
    <div class="sl-w-1 sl-mt-2 sl-mr-3 sl--ml-3 sl-border-t"></div>
    <div class="sl-stack sl-stack--vertical sl-stack--1 sl-flex sl-flex-1 sl-flex-col sl-items-stretch sl-max-w-full sl-ml-2 ">
        <div class="sl-flex sl-items-center sl-max-w-full">
                                        <div class="sl-flex sl-items-baseline sl-text-base">
                    <div class="sl-font-mono sl-font-semibold sl-mr-2">titulo</div>
                                            <span class="sl-truncate sl-text-muted">string</span>
                                    </div>
                                    <div class="sl-flex-1 sl-h-px sl-mx-3"></div>
                    <span class="sl-ml-2 sl-text-warning">required</span>
                                    </div>
                <div class="sl-prose sl-markdown-viewer" style="font-size: 12px;">
            <p>El campo value no debe ser mayor que 80 caracteres.</p>
        </div>
                                    <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-baseline sl-text-muted">
                <span>Example:</span> <!-- <span> important for spacing -->
                <div class="sl-flex sl-flex-1 sl-flex-wrap" style="gap: 4px;">
                    <div class="sl-max-w-full sl-break-all sl-px-1 sl-bg-canvas-tint sl-text-muted sl-rounded sl-border">
                        kgrimwhkahedfyc
                    </div>
                </div>
            </div>
            </div>
</div>

            </div>
    <div class="expandable sl-text-sm sl-border-l sl-ml-px">
        <div class="sl-flex sl-relative sl-max-w-full sl-py-2 sl-pl-3">
    <div class="sl-w-1 sl-mt-2 sl-mr-3 sl--ml-3 sl-border-t"></div>
    <div class="sl-stack sl-stack--vertical sl-stack--1 sl-flex sl-flex-1 sl-flex-col sl-items-stretch sl-max-w-full sl-ml-2 ">
        <div class="sl-flex sl-items-center sl-max-w-full">
                                        <div class="sl-flex sl-items-baseline sl-text-base">
                    <div class="sl-font-mono sl-font-semibold sl-mr-2">contenido</div>
                                            <span class="sl-truncate sl-text-muted">string</span>
                                    </div>
                                    <div class="sl-flex-1 sl-h-px sl-mx-3"></div>
                    <span class="sl-ml-2 sl-text-warning">required</span>
                                    </div>
                <div class="sl-prose sl-markdown-viewer" style="font-size: 12px;">
            <p>Puede usar Markdown para el formato del contenido, ejemplo: <br/><strong><em>bold text</em></strong><br/><em><em>italic text</em></em><br/>[text] (URL)<br><code>inline fixed-width code</code><br/><code>pre-formatted fixed-width code block</code></p>
        </div>
                                    <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-baseline sl-text-muted">
                <span>Example:</span> <!-- <span> important for spacing -->
                <div class="sl-flex sl-flex-1 sl-flex-wrap" style="gap: 4px;">
                    <div class="sl-max-w-full sl-break-all sl-px-1 sl-bg-canvas-tint sl-text-muted sl-rounded sl-border">
                        nobis
                    </div>
                </div>
            </div>
            </div>
</div>

            </div>
    <div class="expandable sl-text-sm sl-border-l sl-ml-px">
        <div class="sl-flex sl-relative sl-max-w-full sl-py-2 sl-pl-3">
    <div class="sl-w-1 sl-mt-2 sl-mr-3 sl--ml-3 sl-border-t"></div>
    <div class="sl-stack sl-stack--vertical sl-stack--1 sl-flex sl-flex-1 sl-flex-col sl-items-stretch sl-max-w-full sl-ml-2 ">
        <div class="sl-flex sl-items-center sl-max-w-full">
                                        <div class="sl-flex sl-items-baseline sl-text-base">
                    <div class="sl-font-mono sl-font-semibold sl-mr-2">image_path</div>
                                            <span class="sl-truncate sl-text-muted">file</span>
                                    </div>
                                    <div class="sl-flex-1 sl-h-px sl-mx-3"></div>
                    <span class="sl-ml-2 sl-text-warning">required</span>
                                    </div>
                <div class="sl-prose sl-markdown-viewer" style="font-size: 12px;">
            <p>El campo value debe ser una imagen.</p>
        </div>
                                    <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-baseline sl-text-muted">
                <span>Example:</span> <!-- <span> important for spacing -->
                <div class="sl-flex sl-flex-1 sl-flex-wrap" style="gap: 4px;">
                    <div class="sl-max-w-full sl-break-all sl-px-1 sl-bg-canvas-tint sl-text-muted sl-rounded sl-border">
                        C:\Users\Miguel\AppData\Local\Temp\phpBCE9.tmp
                    </div>
                </div>
            </div>
            </div>
</div>

            </div>
                            </div>
                        </div>
                    
                                    </div>
            </div>
        </div>

        <div data-testid="two-column-right" class="sl-relative sl-w-2/5 sl-ml-16" style="max-width: 500px;">
            <div class="sl-stack sl-stack--vertical sl-stack--6 sl-flex sl-flex-col sl-items-stretch">

                                    <div class="sl-inverted">
    <div class="sl-overflow-y-hidden sl-rounded-lg">
        <form class="TryItPanel sl-bg-canvas-100 sl-rounded-lg"
              data-method="POST"
              data-path="api/publication"
              data-hasfiles="1"
              data-hasjsonbody="0">
                            <div class="sl-panel sl-outline-none sl-w-full expandable">
                    <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-4 sl-pl-3 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-cursor-pointer sl-select-none"
                         role="button">
                        <div class="sl-flex sl-flex-1 sl-items-center sl-h-lg">
                            <div class="sl-flex sl-items-center sl-mr-1.5 expansion-chevrons expansion-chevrons-solid expanded">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                     data-icon="caret-down"
                                     class="svg-inline--fa fa-caret-down fa-fw sl-icon" role="img"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path fill="currentColor"
                                          d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z"></path>
                                </svg>
                            </div>
                            Auth
                        </div>
                    </div>
                    <div class="sl-panel__content-wrapper sl-bg-canvas-100 children" role="region">
                        <div class="ParameterGrid sl-p-4">
                            <label aria-hidden="true"
                                   for="auth-POSTapi-publication">Authorization</label>
                            <span class="sl-mx-3">:</span>
                            <div class="sl-flex sl-flex-1">
                                <div class="sl-input sl-flex-1 sl-relative">
                                    <code>Bearer </code>
                                    <input aria-label="Authorization"
                                           id="auth-POSTapi-publication"
                                           data-component="header"
                                           data-prefix="Bearer "
                                           name="Authorization"
                                           placeholder="{YOUR_AUTH_KEY}"
                                           class="auth-value sl-relative sl-w-3/5 sl-h-md sl-text-base sl-rounded sl-border-transparent hover:sl-border-input focus:sl-border-primary sl-border">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                            <div class="sl-panel sl-outline-none sl-w-full expandable">
                    <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-4 sl-pl-3 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-cursor-pointer sl-select-none"
                         role="button">
                        <div class="sl-flex sl-flex-1 sl-items-center sl-h-lg">
                            <div class="sl-flex sl-items-center sl-mr-1.5 expansion-chevrons expansion-chevrons-solid expanded">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                     data-icon="caret-down"
                                     class="svg-inline--fa fa-caret-down fa-fw sl-icon" role="img"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path fill="currentColor"
                                          d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z"></path>
                                </svg>
                            </div>
                            Headers
                        </div>
                    </div>
                    <div class="sl-panel__content-wrapper sl-bg-canvas-100 children" role="region">
                        <div class="ParameterGrid sl-p-4">
                                                                                                                            <label aria-hidden="true"
                                       for="header-POSTapi-publication-Content-Type">Content-Type</label>
                                <span class="sl-mx-3">:</span>
                                <div class="sl-flex sl-flex-1">
                                    <div class="sl-input sl-flex-1 sl-relative">
                                        <input aria-label="Content-Type" name="Content-Type"
                                               id="header-POSTapi-publication-Content-Type"
                                               value="multipart/form-data" data-component="header"
                                               class="sl-relative sl-w-full sl-h-md sl-text-base sl-pr-2.5 sl-pl-2.5 sl-rounded sl-border-transparent hover:sl-border-input focus:sl-border-primary sl-border">
                                    </div>
                                </div>
                                                                                            <label aria-hidden="true"
                                       for="header-POSTapi-publication-Accept">Accept</label>
                                <span class="sl-mx-3">:</span>
                                <div class="sl-flex sl-flex-1">
                                    <div class="sl-input sl-flex-1 sl-relative">
                                        <input aria-label="Accept" name="Accept"
                                               id="header-POSTapi-publication-Accept"
                                               value="application/json" data-component="header"
                                               class="sl-relative sl-w-full sl-h-md sl-text-base sl-pr-2.5 sl-pl-2.5 sl-rounded sl-border-transparent hover:sl-border-input focus:sl-border-primary sl-border">
                                    </div>
                                </div>
                                                    </div>
                    </div>
                </div>
            
            
            
                            <div class="sl-panel sl-outline-none sl-w-full expandable">
                    <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-4 sl-pl-3 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-cursor-pointer sl-select-none"
                         role="button">
                        <div class="sl-flex sl-flex-1 sl-items-center sl-h-lg">
                            <div class="sl-flex sl-items-center sl-mr-1.5 expansion-chevrons expansion-chevrons-solid expanded">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                     data-icon="caret-down"
                                     class="svg-inline--fa fa-caret-down fa-fw sl-icon" role="img"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path fill="currentColor"
                                          d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z"></path>
                                </svg>
                            </div>
                            Body
                        </div>
                    </div>
                    <div class="sl-panel__content-wrapper sl-bg-canvas-100 children" role="region">
                                                    <div class="ParameterGrid sl-p-4">
                                                                                                        <label aria-hidden="true"
                                           for="bodyparam-POSTapi-publication-titulo">titulo</label>
                                    <span class="sl-mx-3">:</span>
                                    <div class="sl-flex sl-flex-1">
                                        <div class="sl-input sl-flex-1 sl-relative">
                                                                                            <input aria-label="titulo" name="titulo"
                                                       id="bodyparam-POSTapi-publication-titulo"
                                                       placeholder="El campo value no debe ser mayor que 80 caracteres."
                                                       value="kgrimwhkahedfyc" data-component="body"
                                                       class="sl-relative sl-w-full sl-h-md sl-text-base sl-pr-2.5 sl-pl-2.5 sl-rounded sl-border-transparent hover:sl-border-input focus:sl-border-primary sl-border"
                                                >
                                                                                    </div>
                                    </div>
                                                                                                        <label aria-hidden="true"
                                           for="bodyparam-POSTapi-publication-contenido">contenido</label>
                                    <span class="sl-mx-3">:</span>
                                    <div class="sl-flex sl-flex-1">
                                        <div class="sl-input sl-flex-1 sl-relative">
                                                                                            <input aria-label="contenido" name="contenido"
                                                       id="bodyparam-POSTapi-publication-contenido"
                                                       placeholder="Puede usar Markdown para el formato del contenido, ejemplo: &lt;br/&gt;&lt;strong&gt;*bold text*&lt;/strong&gt;&lt;br/&gt;&lt;em&gt;_italic text_&lt;/em&gt;&lt;br/&gt;[text] (URL)&lt;br&gt;`inline fixed-width code`&lt;br/&gt;```pre-formatted fixed-width code block```"
                                                       value="nobis" data-component="body"
                                                       class="sl-relative sl-w-full sl-h-md sl-text-base sl-pr-2.5 sl-pl-2.5 sl-rounded sl-border-transparent hover:sl-border-input focus:sl-border-primary sl-border"
                                                >
                                                                                    </div>
                                    </div>
                                                                                                        <label aria-hidden="true"
                                           for="bodyparam-POSTapi-publication-image_path">image_path</label>
                                    <span class="sl-mx-3">:</span>
                                    <div class="sl-flex sl-flex-1">
                                        <div class="sl-input sl-flex-1 sl-relative">
                                                                                            <input aria-label="image_path" name="image_path"
                                                       id="bodyparam-POSTapi-publication-image_path"
                                                       type="file" data-component="body"
                                                       class="sl-relative sl-w-full sl-h-md sl-text-base sl-pr-2.5 sl-pl-2.5 sl-rounded sl-border-transparent hover:sl-border-input focus:sl-border-primary sl-border"
                                                >
                                                                                    </div>
                                    </div>
                                                            </div>
                                            </div>
                </div>
            
            <div class="SendButtonHolder sl-mt-4 sl-p-4 sl-pt-0">
                <div class="sl-stack sl-stack--horizontal sl-stack--2 sl-flex sl-flex-row sl-items-center">
                    <button type="button" data-endpoint="POSTapi-publication"
                            class="tryItOut-btn sl-button sl-h-sm sl-text-base sl-font-medium sl-px-1.5 sl-bg-primary hover:sl-bg-primary-dark active:sl-bg-primary-darker disabled:sl-bg-canvas-100 sl-text-on-primary disabled:sl-text-body sl-rounded sl-border-transparent sl-border disabled:sl-opacity-70"
                    >
                        Send Request 💥
                    </button>
                </div>
            </div>

            <div data-endpoint="POSTapi-publication"
                 class="tryItOut-error expandable sl-panel sl-outline-none sl-w-full" hidden>
                <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-4 sl-pl-3 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-cursor-pointer sl-select-none"
                     role="button">
                    <div class="sl-flex sl-flex-1 sl-items-center sl-h-lg">
                        <div class="sl-flex sl-items-center sl-mr-1.5 expansion-chevrons expansion-chevrons-solid expanded">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                 data-icon="caret-down"
                                 class="svg-inline--fa fa-caret-down fa-fw sl-icon" role="img"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path fill="currentColor"
                                      d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z"></path>
                            </svg>
                        </div>
                        Request failed with error
                    </div>
                </div>
                <div class="sl-panel__content-wrapper sl-bg-canvas-100 children" role="region">
                    <div class="sl-panel__content sl-p-4">
                        <p class="sl-pb-2"><strong class="error-message"></strong></p>
                        <p class="sl-pb-2">Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</p>
                    </div>
                </div>
            </div>

                <div data-endpoint="POSTapi-publication"
                     class="tryItOut-response expandable sl-panel sl-outline-none sl-w-full" hidden>
                    <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-4 sl-pl-3 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-cursor-pointer sl-select-none"
                         role="button">
                        <div class="sl-flex sl-flex-1 sl-items-center sl-h-lg">
                            <div class="sl-flex sl-items-center sl-mr-1.5 expansion-chevrons expansion-chevrons-solid expanded">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                     data-icon="caret-down"
                                     class="svg-inline--fa fa-caret-down fa-fw sl-icon" role="img"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path fill="currentColor"
                                          d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z"></path>
                                </svg>
                            </div>
                            Received response
                        </div>
                    </div>
                    <div class="sl-panel__content-wrapper sl-bg-canvas-100 children" role="region">
                        <div class="sl-panel__content sl-p-4">
                            <p class="sl-pb-2 response-status"></p>
                            <pre><code class="sl-pb-2 response-content language-json"
                                       data-empty-response-text="<Empty response>"
                                       style="max-height: 300px;"></code></pre>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
                
                                            <div class="sl-panel sl-outline-none sl-w-full sl-rounded-lg">
                            <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-3 sl-pl-4 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-select-none">
                                <div class="sl-flex sl-flex-1 sl-items-center sl-h-lg">
                                    <div class="sl--ml-2">
                                        Example request:
                                        <select class="example-request-lang-toggle sl-text-base"
                                                aria-label="Request Sample Language"
                                                onchange="switchExampleLanguage(event.target.value);">
                                                                                            <option>javascript</option>
                                                                                            <option>php</option>
                                                                                            <option>bash</option>
                                                                                            <option>python</option>
                                                                                    </select>
                                    </div>
                                </div>
                            </div>
                                                            <div class="sl-bg-canvas-100 example-request example-request-javascript"
                                     style="">
                                    <div class="sl-px-0 sl-py-1">
                                        <div style="max-height: 400px;" class="sl-rounded">
                                            <pre><code class="language-javascript">const url = new URL(
    "http://telgmnotify.qtokesoft.cu/api/publication"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('titulo', 'kgrimwhkahedfyc');
body.append('contenido', 'nobis');
body.append('image_path', document.querySelector('input[name="image_path"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>                                        </div>
                                    </div>
                                </div>
                                                            <div class="sl-bg-canvas-100 example-request example-request-php"
                                     style="display: none;">
                                    <div class="sl-px-0 sl-py-1">
                                        <div style="max-height: 400px;" class="sl-rounded">
                                            <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'http://telgmnotify.qtokesoft.cu/api/publication',
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_KEY}',
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'titulo',
                'contents' =&gt; 'kgrimwhkahedfyc'
            ],
            [
                'name' =&gt; 'contenido',
                'contents' =&gt; 'nobis'
            ],
            [
                'name' =&gt; 'image_path',
                'contents' =&gt; fopen('C:\Users\Miguel\AppData\Local\Temp\phpBCE9.tmp', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>                                        </div>
                                    </div>
                                </div>
                                                            <div class="sl-bg-canvas-100 example-request example-request-bash"
                                     style="display: none;">
                                    <div class="sl-px-0 sl-py-1">
                                        <div style="max-height: 400px;" class="sl-rounded">
                                            <pre><code class="language-bash">curl --request POST \
    "http://telgmnotify.qtokesoft.cu/api/publication" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "titulo=kgrimwhkahedfyc" \
    --form "contenido=nobis" \
    --form "image_path=@C:\Users\Miguel\AppData\Local\Temp\phpBCE9.tmp" </code></pre>                                        </div>
                                    </div>
                                </div>
                                                            <div class="sl-bg-canvas-100 example-request example-request-python"
                                     style="display: none;">
                                    <div class="sl-px-0 sl-py-1">
                                        <div style="max-height: 400px;" class="sl-rounded">
                                            <pre><code class="language-python">import requests
import json

url = 'http://telgmnotify.qtokesoft.cu/api/publication'
files = {
  'image_path': open('C:\Users\Miguel\AppData\Local\Temp\phpBCE9.tmp', 'rb')
}
payload = {
    "titulo": "kgrimwhkahedfyc",
    "contenido": "nobis"
}
headers = {
  'Authorization': 'Bearer {YOUR_AUTH_KEY}',
  'Content-Type': 'multipart/form-data',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, files=files, data=payload)
response.json()</code></pre>                                        </div>
                                    </div>
                                </div>
                                                    </div>
                    
                                            <div class="sl-panel sl-outline-none sl-w-full sl-rounded-lg">
                            <div class="sl-panel__titlebar sl-flex sl-items-center sl-relative focus:sl-z-10 sl-text-base sl-leading-none sl-pr-3 sl-pl-4 sl-bg-canvas-200 sl-text-body sl-border-input focus:sl-border-primary sl-select-none">
                                <div class="sl-flex sl-flex-1 sl-items-center sl-py-2">
                                    <div class="sl--ml-2">
                                        <div class="sl-h-sm sl-text-base sl-font-medium sl-px-1.5 sl-text-muted sl-rounded sl-border-transparent sl-border">
                                            <div class="sl-mb-2 sl-inline-block">Example response:</div>
                                            <div class="sl-mb-2 sl-inline-block">
                                                <select
                                                        class="example-response-POSTapi-publication-toggle sl-text-base"
                                                        aria-label="Response sample"
                                                        onchange="switchExampleResponse('POSTapi-publication', event.target.value);">
                                                                                                            <option value="0">200</option>
                                                                                                    </select></div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button"
                                        class="sl-button sl-h-sm sl-text-base sl-font-medium sl-px-1.5 hover:sl-bg-canvas-50 active:sl-bg-canvas-100 sl-text-muted hover:sl-text-body focus:sl-text-body sl-rounded sl-border-transparent sl-border disabled:sl-opacity-70">
                                    <div class="sl-mx-0">
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="copy"
                                             class="svg-inline--fa fa-copy fa-fw fa-sm sl-icon" role="img"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="currentColor"
                                                  d="M384 96L384 0h-112c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48H464c26.51 0 48-21.49 48-48V128h-95.1C398.4 128 384 113.6 384 96zM416 0v96h96L416 0zM192 352V128h-144c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h192c26.51 0 48-21.49 48-48L288 416h-32C220.7 416 192 387.3 192 352z"></path>
                                        </svg>
                                    </div>
                                </button>
                            </div>
                                                            <div class="sl-panel__content-wrapper sl-bg-canvas-100 example-response-POSTapi-publication example-response-POSTapi-publication-0"
                                     style=" "
                                >
                                    <div class="sl-panel__content sl-p-0">                                                                                                                                
                                            <pre><code style="max-height: 300px;"
                                                       class="language-json sl-overflow-x-auto sl-overflow-y-auto">{
    &quot;message&quot;: &quot;Su contenido se ha puesto en la cola de salida&quot;
}</code></pre>
                                                                            </div>
                                </div>
                                                    </div>
                            </div>
    </div>
</div>

            

            <div class="sl-prose sl-markdown-viewer sl-my-5">
                
            </div>
        </div>

    </div>

    <template id="expand-chevron">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-fw sl-icon sl-text-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
            <path fill="currentColor" d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z"></path>
        </svg>
    </template>

    <template id="expanded-chevron">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-down" class="svg-inline--fa fa-chevron-down fa-fw sl-icon sl-text-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path fill="currentColor" d="M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z"></path>
        </svg>
    </template>

    <template id="expand-chevron-solid">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-right" class="svg-inline--fa fa-caret-right fa-fw sl-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
            <path fill="currentColor" d="M118.6 105.4l128 127.1C252.9 239.6 256 247.8 256 255.1s-3.125 16.38-9.375 22.63l-128 127.1c-9.156 9.156-22.91 11.9-34.88 6.943S64 396.9 64 383.1V128c0-12.94 7.781-24.62 19.75-29.58S109.5 96.23 118.6 105.4z"></path>
        </svg>
    </template>

    <template id="expanded-chevron-solid">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-down" class="svg-inline--fa fa-caret-down fa-fw sl-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
            <path fill="currentColor" d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z"></path>
        </svg>
    </template>
</body>

</html>