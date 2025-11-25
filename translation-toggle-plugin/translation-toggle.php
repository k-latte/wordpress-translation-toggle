<?php
/*
* Plugin Name: Translation Toggle
* Plugin URI: https://manuwerks.com
* Description: Displays content in different languages ​​with a floating toggle. Requires translation-block-en AND translation-block-es classes to be present on the page.
* Version: 0.1.1
* Author: ManuWerks
* Author URI: https://manuwerks.com
* License: GPLv2 or later
*/

// Definir las rutas de las banderas de forma global
$plugin_dir = plugin_dir_url( __FILE__ );
$flag_en = $plugin_dir . 'flags/en.png';
$flag_es = $plugin_dir . 'flags/es.png';

// Función para comprobar el idioma del navegador y añadir el toggle al footer del sitio
function add_translation_toggle() {
    global $flag_en, $flag_es;
    ?>
    <!-- add toggle: -->
    <div id="translation-toggle" style="display: none;position: fixed;bottom: 20px;right: 20px;padding: 10px;background-color: #FFF;color: #333;cursor: pointer;font-weight: bold;border-radius: 7px;box-shadow: 0px 0px 13px #DDD;" data-lang="EN">
        <img src="<?php echo $flag_en; ?>" alt="English" style="vertical-align: text-bottom; margin-right: 5px;">
        <span>EN</span>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var userLang = navigator.language || navigator.userLanguage;

        // Comprobar si hay elementos con clase .translation-block-en y .translation-block-es:
        var enBlocks = document.querySelectorAll('.translation-block-en');
        var esBlocks = document.querySelectorAll('.translation-block-es');
        // Si no hay bloques de traducción en la página, ocultar el toggle
        if (enBlocks.length === 0 || esBlocks.length === 0) {
            document.getElementById('translation-toggle').style.display = 'none';  // redundant: toggle starts hidden (display: none)
            return; // salir
        } else {
            document.getElementById('translation-toggle').style.display = 'block'; // display if needed
        }

        var langToggle = document.getElementById('translation-toggle');
        if (userLang.startsWith('es')) {
            enBlocks.forEach(function(block) {
                block.style.display = 'none';
            });
            esBlocks.forEach(function(block) {
                block.style.display = 'block';
            });
            langToggle.innerHTML = '<img src="<?php echo $flag_en; ?>" alt="English" style="vertical-align: text-bottom; margin-right: 5px;"> EN';
            langToggle.setAttribute('data-lang', 'EN');
        } else {
            esBlocks.forEach(function(block) {
                block.style.display = 'none';
            });
            enBlocks.forEach(function(block) {
                block.style.display = 'block';
            });
            langToggle.innerHTML = '<img src="<?php echo $flag_es; ?>" alt="Español" style="vertical-align: text-bottom; margin-right: 5px;"> ES';
            langToggle.setAttribute('data-lang', 'ES');
        }

    });
    </script>
    <?php
}

// Añadir el script de JavaScript para controlar el toggle
function translation_toggle_script() {
    global $flag_en, $flag_es;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para activar/desactivar bloques de traducción
        function toggle_translation_blocks() {
            // Comprobar el estado actual del toggle
            var toggleState = this.getAttribute('data-lang');
            var enBlocks = document.querySelectorAll('.translation-block-en');
            var esBlocks = document.querySelectorAll('.translation-block-es');

            // Si el estado actual es 'EN', activar bloques en inglés y desactivar bloques en español
            if (toggleState === 'EN') {
                esBlocks.forEach(function(block) {
                    block.style.display = 'none';
                });
                enBlocks.forEach(function(block) {
                    block.style.display = 'block';
                });
                this.innerHTML = '<img src="<?php echo $flag_es; ?>" alt="Español" style="vertical-align: text-bottom; margin-right: 5px;"> ES';
                this.setAttribute('data-lang', 'ES');
            } else { // Si el estado actual es 'ES', hacer lo contrario
                enBlocks.forEach(function(block) {
                    block.style.display = 'none';
                });
                esBlocks.forEach(function(block) {
                    block.style.display = 'block';
                });
                this.innerHTML = '<img src="<?php echo $flag_en; ?>" alt="English" style="vertical-align: text-bottom; margin-right: 5px;"> EN';
                this.setAttribute('data-lang', 'EN');
            }
        }

        // Añadir evento de clic al toggle
        document.getElementById('translation-toggle').addEventListener('click', toggle_translation_blocks);
    });
    </script>
    <?php
}

// Añadir todo al footer del sitio
add_action('wp_footer', 'add_translation_toggle');
add_action('wp_footer', 'translation_toggle_script');
