<div>
    <style>
        /* open-sans-300 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 300;
            src: url("{{ asset('fonts/open-sans-v34-latin-300.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-300.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-300italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: italic;
            font-weight: 300;
            src: url("{{ asset('fonts/open-sans-v34-latin-300italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-300italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-regular - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 400;
            src: url("{{ asset('fonts/open-sans-v34-latin-regular.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-regular.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: italic;
            font-weight: 400;
            src: url("{{ asset('fonts/open-sans-v34-latin-italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-600 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 600;
            src: url("{{ asset('fonts/open-sans-v34-latin-600.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-600.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-600italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: italic;
            font-weight: 600;
            src: url("{{ asset('fonts/open-sans-v34-latin-600italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-600italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-700 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 700;
            src: url("{{ asset('fonts/open-sans-v34-latin-700.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-700.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-700italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Open Sans';
            font-style: italic;
            font-weight: 700;
            src: url("{{ asset('fonts/open-sans-v34-latin-700italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/open-sans-v34-latin-700italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-300 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: normal;
            font-weight: 300;
            src: url("{{ asset('fonts/nunito-v25-latin-300.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-300.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-300italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: italic;
            font-weight: 300;
            src: url("{{ asset('fonts/nunito-v25-latin-300italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-300italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-regular - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: normal;
            font-weight: 400;
            src: url("{{ asset('fonts/nunito-v25-latin-regular.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-regular.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: italic;
            font-weight: 400;
            src: url("{{ asset('fonts/nunito-v25-latin-italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-600 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: normal;
            font-weight: 600;
            src: url("{{ asset('fonts/nunito-v25-latin-600.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-600.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-600italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: italic;
            font-weight: 600;
            src: url("{{ asset('fonts/nunito-v25-latin-600italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-600italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-700 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: normal;
            font-weight: 700;
            src: url("{{ asset('fonts/nunito-v25-latin-700.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-700.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* nunito-700italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Nunito';
            font-style: italic;
            font-weight: 700;
            src: url("{{ asset('fonts/nunito-v25-latin-700italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/nunito-v25-latin-700italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-300 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 300;
            src: url("{{ asset('fonts/poppins-v20-latin-300.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-300.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-300italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 300;
            src: url("{{ asset('fonts/poppins-v20-latin-300italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-300italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-regular - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url("{{ asset('fonts/poppins-v20-latin-regular.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-regular.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 400;
            src: url("{{ asset('fonts/poppins-v20-latin-italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-500 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 500;
            src: url("{{ asset('fonts/poppins-v20-latin-500.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-500.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-500italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 500;
            src: url("{{ asset('fonts/poppins-v20-latin-500italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-500italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-600 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 600;
            src: url("{{ asset('fonts/poppins-v20-latin-600.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-600.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-600italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 600;
            src: url("{{ asset('fonts/poppins-v20-latin-600italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-600italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-700 - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 700;
            src: url("{{ asset('fonts/poppins-v20-latin-700.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-700.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* poppins-700italic - latin */
        @font-face {
            font-display: swap;
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Poppins';
            font-style: italic;
            font-weight: 700;
            src: url("{{ asset('fonts/poppins-v20-latin-700italic.woff2') }}") format('woff2'),
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/poppins-v20-latin-700italic.woff') }}") format('woff');
            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }
    </style>
</div>