<?php
/* 
Plugin Name: Plugin POLs - AgenciaLINK.com
Plugin URI: https://suporte.agencialink.com.br/b2cor/plugin-wp-pols
Description:  Este plugin facilita a Integrar em suas páginas  do seu WordPress os formulários do POLs (Sistemas de Pesquisas Online) que além de permitir que seus consumidores possam realizar pesquisas online de planos de saúde e odontológicos em tempo real, ele também esta integrado com o B2Cor CRM, que ao ser utilizado irá gravar os novos leads capturados que serão enviados automaticamente para o B2Cor CRM e Funil de Vendas facilitando muito seu trabalho e agilidade. Com este plugin é possível customizar ele de duas maneiras: Inserindo um botão que chamará a ferramenta diretamente para o formulário desejado ou criando um formulário diretamente na sua página que após capturar o lead o enviará para a o formulário de cotação desejado.
Version: 1.7.33
Author: agencialink.com
Author URI: https://agencialink.com/
*/

// RESGITRA O MENU

add_action('admin_menu', 'menuAgenciaLinkPols');

function menuAgenciaLinkPols()
{
    add_menu_page(
        'Plugin POLs - AgenciaLINK.com',
        'Plugin POLs - AgenciaLINK.com',
        'manage_options',
        'agencialink-pols',
        'agenciaLinkPolsOptions',
        'dashicons-welcome-learn-more'
    );
    add_action('admin_init', 'registerAgenciaLinkPolsOptions');
}

// CRIA AS OPÇÕES NO BANCO DE DADOS

function registerAgenciaLinkPolsOptions()
{
    register_setting('agencia_link_pols_options', 'alink_pols_key');
    register_setting('agencia_link_pols_options', 'alink_enable_jquery');
}

// LINK DIRETO PARA A PAGINA DE OPÇÕES

function linkPolsOptionsAgenciaLink($links)
{
    $settings_link = '<a href="/wp-admin/admin.php?page=agencialink-pols">Configurações</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'linkPolsOptionsAgenciaLink');

// RESGITRA O SHORTCODE DO FORMULÁRIO

function shortcodePolsAgenciaLink($attr)
{

    $attr = shortcode_atts(
        [
            'campo-telefone' => 'sim',
            'campo-mensagem' => 'sim',

            'tipo-cotacao' => 'saude',
            'formulario' => '',

            'cor-fundo-campos' => 'ffffff',
            'cor-borda-campos' => 'DCDFE6',
            'cor-fonte-campos' => '333333',

            'cor-label' => '606266',
            'cor-alertas' => 'F56C6C',

            'texto-botao' => 'Enviar',
            'cor-fundo-botao' => '007bff',
            'cor-fonte-botao' => 'ffffff',

            'msg-sucesso' => 'Sua mensagem foi enviada com sucesso! Em breve um de nossos consultores entrará em contato.',
            'cor-msg-sucesso' => '007bff',
            'fonte-msg-sucesso' => '14px',

            'espaco-entre-campos' => '22px',
            'largura-form' => '600px',
            'layout-form' => 'duas-colunas',

            'altura-iframe-desktop' => '',
            'altura-iframe-mobile' => '',
        ],
        $attr,
        'alink-form-pols'
    );

    ob_start();

    $b2cor_key = get_option('alink_pols_key');

    if ($b2cor_key) {

        $telefone = str_replace(
            array(' ', '&', '&', 'á', 'ã', 'à', 'é', 'ê', 'í', 'ç', 'õ', 'ô', 'ó', 'ú', '-', "'"),
            array('', '', '', 'a', 'a', 'a', 'e', 'e', 'i', 'c', 'o', 'o', 'o', 'u', '', ''),
            $attr['campo-telefone']
        );

        $mensagem = str_replace(
            array(' ', '&', '&', 'á', 'ã', 'à', 'é', 'ê', 'í', 'ç', 'õ', 'ô', 'ó', 'ú', '-', "'"),
            array('', '', '', 'a', 'a', 'a', 'e', 'e', 'i', 'c', 'o', 'o', 'o', 'u', '', ''),
            $attr['campo-mensagem']
        );

        $link .= "https://formulario-pols.agencialink.com.br/?"; // link do formulário
        $link .= "b2cor_key=$b2cor_key&";
        $link .= "telefone=" . $telefone . "&";
        $link .= "mensagem=" . $mensagem . "&";

        $link .= "tipo-cotacao=" . $attr['tipo-cotacao'] . "&";
        $link .= "formulario=" . $attr['formulario'] . "&";

        $link .= "cor-fundo-campos=" . str_replace("#", '', $attr['cor-fundo-campos']) . "&";
        $link .= "cor-borda-campos=" . str_replace("#", '', $attr['cor-borda-campos']) . "&";
        $link .= "cor-fonte-campos=" . str_replace("#", '', $attr['cor-fonte-campos']) . "&";

        $link .= "cor-label=" . str_replace("#", '', $attr['cor-label']) . "&";
        $link .= "cor-alertas=" . str_replace("#", '', $attr['cor-alertas']) . "&";

        $link .= "texto-botao=" . $attr['texto-botao'] . "&";
        $link .= "cor-fundo-botao=" . str_replace("#", '', $attr['cor-fundo-botao']) . "&";
        $link .= "cor-fonte-botao=" . str_replace("#", '', $attr['cor-fonte-botao']) . "&";

        $link .= "msg-sucesso=" . $attr['msg-sucesso'] . "&";
        $link .= "cor-msg-sucesso=" . str_replace("#", '', $attr['cor-msg-sucesso']) . "&";
        $link .= "fonte-msg-sucesso=" . $attr['fonte-msg-sucesso'] . "&";

        $link .= "espaco-entre-campos=" . $attr['espaco-entre-campos'] . "&";
        $link .= "largura-form=" . $attr['largura-form'] . "&";
        $link .= "layout-form=" . $attr['layout-form'] . "&";

        $link .= "altura-iframe-desktop=" . $attr['altura-iframe-desktop'] . "&";
        $link .= "altura-iframe-mobile=" . $attr['altura-iframe-mobile'] . "&";

        $link .= "origem=" . get_site_url();

        $class = "all-fields";
        $desktop_height = '500px';
        $mobile_height = '500px';

        if ($attr['layout-form'] == "duas-colunas") {

            if ($telefone == "sim" && $mensagem  == "sim") {
                $class = "all-fields";
                $desktop_height = '500px';
                $mobile_height = '500px';
            }

            if ($telefone == "nao" && $mensagem  == "nao") {
                $class = "no-fields";
                $desktop_height = '292px';
                $mobile_height = '292px';
            }

            if ($telefone == "nao" && $mensagem == "sim") {
                $class = "have-msg";
                $desktop_height = '327px';
                $mobile_height = '410px';
            }

            if ($telefone == "sim" && $mensagem  == "nao") {
                $class = "have-phone";
                $desktop_height = '210px';
                $mobile_height = '370px';
            }
        } else {

            if ($telefone == "sim" && $mensagem  == "sim") {
                $class = "all-fields";
                $desktop_height = '540px';
                $mobile_height = '540px';
            }

            if ($telefone == "nao" && $mensagem  == "nao") {
                $class = "no-fields";
                $desktop_height = '322px';
                $mobile_height = '322px';
            }

            if ($telefone == "nao" && $mensagem == "sim") {
                $class = "have-msg";
                $desktop_height = '450px';
                $mobile_height = '450px';
            }

            if ($telefone == "sim" && $mensagem  == "nao") {
                $class = "have-phone";
                $desktop_height = '400px';
                $mobile_height = '400px';
            }
        }

        if ($attr['altura-iframe-desktop']) {
            $desktop_height = $attr['altura-iframe-desktop'];
        }

        if ($attr['altura-iframe-mobile']) {
            $mobile_height =  $attr['altura-iframe-mobile'];
        }
?>

        <iframe id="agencialink-window" class="<?php echo $class; ?>" src="<?php echo $link; ?>"></iframe>

        <style>
            #agencialink-window.<?php echo $class; ?> {
                width: 100%;
                overflow: hidden !important;
                height: <?php echo $desktop_height; ?>;
                border: 0;
                background: transparent;
            }

            @media only screen and (min-device-width : 0px) and (max-width : 874px) {
                #agencialink-window.<?php echo $class; ?> {
                    height: <?php echo $mobile_height; ?>;
                }
            }
        </style>

    <?php } else { ?>

        <?php if (is_user_logged_in()) { ?>

            <div class="alink-message">
                A chave B2cor deve ser inserida na <a href='/wp-admin/admin.php?page=agencialink-pols' target="_blank">página de opções</a>
                da agencialink para gerar o formulário de captação de Leads.
            </div>

            <style>
                .alink-message {
                    text-align: center;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    padding: 20px;
                    border-radius: 9px;
                    font-size: 14px;
                    color: #606266;
                    clear: both;
                }

                .alink-message a {
                    color: #007bff;
                    font-size: 14px;
                }
            </style>

        <?php } ?>

    <?php
    }
    return ob_get_clean();
}

add_shortcode('alink-form-pols', 'shortcodePolsAgenciaLink');

// GERA A APLICAÇÃO PARA CHAMADA DOS FORMULARIOS

function agenciaLinkPolsApp()
{
    $b2cor_key = get_option('alink_pols_key'); // KEY
    if ($b2cor_key) { ?>

        <!-- ELEMENT -->
        <link rel="stylesheet" href="<?php echo plugins_url('agencialink-pols/assets/css/admin/element.css', dirname(__FILE__)); ?>">
        <script src="<?php echo plugins_url('agencialink-pols/assets/js/element.js', dirname(__FILE__)); ?>"></script>

        <div id="alink-pols-app">
            <el-dialog :visible.sync="abrirModal" :before-close="limparModal" width="560px" center append-to-body>
                <div id="alink-form-cotacao"></div>
            </el-dialog>

            <div v-if="redirectToExternal" class="el-loading-mask is-fullscreen">
                <div class="el-loading-spinner"><svg viewBox="25 25 50 50" class="circular">
                        <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <script>
            new Vue({
                el: '#alink-pols-app',
                data: {
                    abrirModal: false,
                    ultimaClasse: null,
                    redirectToExternal: false
                },
                methods: {
                    limparModal() {
                        this.abrirModal = false;
                    }
                },
                mounted() {

                    // DISPIRAR EVENTO EMITIDO DO IFRAME
                    window.addEventListener('message', event => {
                        // console.log('Emitir evento: ', event);
                        if (event.data.alink != undefined) {
                            if (screen.width > 991) {
                                this.abrirModal = true; // abrir o modal
                                setTimeout(() => {
                                    b2cor.abrir('alink-form-cotacao', event.data.alink.tipo_cotacao, event.data.alink.formulario, false, window.location.hostname);
                                }, 100)
                            } else {
                                this.redirectToExternal = true; // exibe o loader full window
                                this.$confirm('Você será redirecionado para o nosso formulário de pesquisa online, clique em OK para continuar.', '', {
                                    confirmButtonText: 'OK',
                                    cancelButtonText: '',
                                    showCancelButton: false,
                                    type: 'info',
                                    center: true
                                }).then(() => {
                                    setTimeout(() => {
                                        b2cor.abrir('alink-form-cotacao', event.data.alink.tipo_cotacao, event.data.alink.formulario, false, window.location.hostname);
                                    }, 100);
                                }).catch(() => {
                                    this.redirectToExternal = false; // exibe o loader full window
                                });
                            }
                        }
                    });

                    let tipo_cotacao = null; // qual cotação será carregada inicialmente. Opções: saude, adesao, odonto, regiao, economia, rede. 
                    let formulario = null; // o tipo de formulário desta cotação. Apenas caso o tipo_cotacao seja saude, adesao ou odonto. Opções:individual, empresarial, familiar. Opcional.
                    let auto_redirecionar = false; // caso seja informado como true, o usuário será redirecionado para a ferramenta, ao invés de abrir no iframe. Opcional.
                    let origem = window.location.origin; // código da origem do acesso do usuário. Opcional.

                    // AGUARDA 1s ANTES DE CAPTURAR TODAS CLASSES .alink
                    setTimeout(() => {

                        let allAlinkClasses = document.querySelectorAll(".alink");
                        // console.log('Elementos .alink capturados: ', allAlinkClasses);

                        for (let i = 0; i < allAlinkClasses.length; i++) {

                            allAlinkClasses[i].addEventListener("click", e => {

                                if (jQueryPols != undefined) {

                                    if (screen.width > 991) {
                                        this.abrirModal = true; // abrir o modal
                                    } else {
                                        this.redirectToExternal = true; // exibe o loader full window
                                    }

                                    console.log('Classe do clique (closest): ', e.target.closest(".alink").className); // captura classes do elemento que contém a classe .alink

                                    let classesElemento = e.target.closest(".alink").className;

                                    // CONDICIONAL SE CLICAR NO MESMO ELEMENTO NÃO RECARRREGAR IFRAME NOVAMENTE
                                    if (this.ultimaClasse != classesElemento) {

                                        this.ultimaClasse = classesElemento; // guarda a última classe clicada

                                        // PARA CADAS CLASSE FAZER AS VERIFICAÇÕES
                                        jQuery.each(classesElemento.split(" "), function(index, classe) {

                                            if (classe.includes('-')) {

                                                let classeServico = classe.split('-');
                                                // console.log(classeServico);

                                                if (classeServico[0] == "alink") {

                                                    tipo_cotacao = classeServico[1]; // TIPO DE COTAÇÃO
                                                    formulario = classeServico[2]; // TIPO DE FORMULÁRIO

                                                    if (tipo_cotacao == "cotacao") {
                                                        tipo_cotacao = "saude"; // padrão
                                                    }

                                                    if (formulario == "formulario") {
                                                        formulario = "individual"; // padrão
                                                    }

                                                    if (classeServico[0]) {
                                                        servico = classeServico[1];
                                                    }

                                                    setTimeout(() => {
                                                        b2cor.abrir('alink-form-cotacao', tipo_cotacao, formulario, auto_redirecionar, origem);
                                                    }, 500);
                                                }
                                            }
                                        });
                                    }

                                } else {
                                    this.$confirm('A biblioteca jQuery não foi encontrada. Habilite nas opções do POLs.', '', {
                                        confirmButtonText: 'OK',
                                        cancelButtonText: '',
                                        showCancelButton: false,
                                        type: 'warning',
                                        center: true
                                    }).then(() => {}).catch(() => {});
                                }
                            });
                        }
                    }, 1000);
                },

            });
        </script>

        <style>
            #alink-form-cotacao {
                width: 100% !important;
            }

            #alink-form-cotacao iframe {
                width: 100% !important;
            }

            .el-dialog {
                margin-top: 60px !important;
            }

            .el-loading-mask.is-fullscreen {
                z-index: 999999 !important;
            }

            .el-dialog__wrapper {
                z-index: 999999 !important;
            }

            .el-dialog--center .el-dialog__body {
                padding: 0px !important;
                min-height: 250px;
            }

            .el-dialog__header {
                padding: 20px 20px 30px !important;
                border-bottom: 1px solid #eee;
            }

            .el-message-box__wrapper {
                z-index: 999999 !important;
            }

            .v-modal {
                z-index: 999998 !important;
            }

            .alink {
                cursor: pointer;
            }

            @media only screen and (min-device-width : 0px) and (max-width : 874px) {

                .el-message-box__wrapper {
                    top: 80px !important;
                }

            }
        </style>

        <?php }
}
//add_action('the_content', 'agenciaLinkPolsApp');

// ADICIONA O POLS APOS O CONTEUDO DA PAFINAS

function agenciaLinkPolsAfterContent($content)
{
    return $content . agenciaLinkPolsApp();
}
add_filter('the_content', 'agenciaLinkPolsAfterContent');

// CHAMA AS BIBLIOTECAS DO FORMULÁRIO ONDE O SHORTCODE EXISTIR

function agenciaLinkPolsLibs()
{
    $b2cor_key = get_option('alink_pols_key'); // KEY
    if ($b2cor_key) {
        $enable_jquery = get_option('alink_enable_jquery'); // JQUERY

        if ($enable_jquery == "sim") { ?>
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- JQUERY -->
        <?php } ?>

        <!-- API B2COR -->
        <script src="https://pols.agencialink.com.br/resources/js/api.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>

        <script>
            b2cor = new b2cor('<?php echo $b2cor_key; ?>');
        </script>

    <?php
    }
}
add_action('wp_head', 'agenciaLinkPolsLibs');

// GERA A PÁGINA DE OPÇÕES

function agenciaLinkPolsOptions()
{ ?>

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <!-- VUE -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <!-- ELEMENT -->
    <link rel="stylesheet" href="<?php echo plugins_url('agencialink-pols/assets/css/admin/element.css', dirname(__FILE__)); ?>">
    <script src="<?php echo plugins_url('agencialink-pols/assets/js/element.js', dirname(__FILE__)); ?>"></script>

    <div id="agencia-link">
        <el-row :gutter="0" align="middle">
            <el-col class="alink-container" :xs="24" :sm="24" :md="12" :span="12">
                <form method="post" action="options.php">
                    <div class="alink-head">
                        <el-row :gutter="20">
                            <el-col class="logo" :xs="12" :span="10">
                                <a href="https://agencialink.com" target="_blank">
                                    <img src="<?php echo plugins_url('agencialink-pols/assets/images/admin/logo.png', dirname(__FILE__)); ?>" alt="Agencialink" />
                                </a>
                            </el-col>
                            <el-col class="logo-pols" :xs="24" :span="8">
                                <a href="https://pols.agencialink.com" target="_blank">
                                    <img src="<?php echo plugins_url('agencialink-pols/assets/images/admin/pols.png', dirname(__FILE__)); ?>" style="width: 159px; margin-top: 8px" alt="POLs" />
                                </a>
                            </el-col>
                            <el-col class="support" :xs="12" :span="6">
                                <el-button @click="openSupport" type="primary" style="font-size: 16px"><i class="wp-menu-image dashicons-before dashicons-welcome-learn-more"></i> Suporte</el-button>
                            </el-col>
                        </el-row>
                    </div>
                    <div class="alink-body">

                        <?php settings_fields('agencia_link_pols_options'); ?>
                        <?php do_settings_sections('agencia_link_pols_options'); ?>

                        <div class="welcome">
                            <p>Olá! Seja bem vindo à agencialink!</p>
                            <p class="txt2">Nesta página você poderá configurar a chave Pols dos nossos formulários de Pesquisa Online e saber como fazer sua chamada nas páginas do seu site. E também, como utilizar o formulário de captura de Leads integrado aos formulários de Pesquisa Online. </p>
                        </div>

                        <h1>Plugin WP POLs <font> (v1.7.33) </font>
                        </h1>

                        <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
                            <el-tab-pane label="Início" name="inicio">
                                <div class="field b2-cor">
                                    <label>
                                        POLs B2cor Key (obrigatório)
                                    </label>
                                    <div class="infos">
                                        Insira a chave no campo abaixo:
                                    </div>
                                    <div>
                                        <input type="text" name="alink_pols_key" value="<?php echo esc_attr(get_option('alink_pols_key')); ?>" placeholder="Ex: ab93adaa54974eXXXXXXXXXXXXXXXXXX" />
                                    </div>
                                </div>

                                <div class="field jquery">
                                    <label>
                                        Habilitar jQuery
                                    </label>
                                    <div class="infos">A API B2cor funciona corretamente com a presença da bilbioteca jQuery, caso seu site não possua está biblioteca habilite está opção.</div>
                                    <div>
                                        <?php
                                        if (get_option('alink_enable_jquery') == "sim") {
                                            $checked = 'checked';
                                        } ?>

                                        <input type="checkbox" id="jquery" name="alink_enable_jquery" value="sim" <?php echo $checked; ?>>
                                        <label for="jquery" style="position: relative; top: -3px"> Marque para habilitar </label>
                                    </div>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane label="Formulários de Pesquisa" name="forms">

                                <div class="field shortcode">
                                    <label style="margin-bottom: 17px">
                                        Como fazer a chamada dos formulários de pesquisa online?
                                    </label>
                                    <div class="infos"> A chamada dos formulários de pesquisa é realizada através da inserção de classes em qualquer elemento HTML do seu site. Veja o exemplo: </div>
                                    <div class="copy">
                                        <input type="text" readonly value='class="alink alink-cotacao-formulario"' />
                                    </div>

                                    <ul class="details">
                                        <li> Explicação das classes: </li>
                                        <li> <strong> - alink</strong>: A primeira classe <strong>"alink"</strong> é responsável pela abertura do modal ao clicar em um elemento com esta classe.</li>
                                        <li> <strong> - alink-cotacao-formulario </strong>: A segunda, <strong>"alink-cotacao-formulario"</strong>, você especifica qual <strong>tipo de cotação</strong> e o <strong>formulário</strong> que deseja exibir no modal. </li>
                                        <li> <strong>OBSERVAÇÃO</strong>: Para o correto funcionamento é obrigatório a inserção das duas classes <strong>"alink"</strong> e <strong>"alink-cotacao-formulario"</strong> separadas por espaço como no exemplo destacado acima. </li>
                                    </ul>
                                </div>

                                <div class="field shortcode">

                                    <label style="margin-bottom: 17px">
                                        Tipos de cotação
                                    </label>

                                    <div class="infos"> Abaixo a lista de todos tipos de cotação disponíveis: </div>

                                    <el-table :data="tableData1" style="width: 100%;margin-bottom: 20px;" row-key="id" border>
                                        <el-table-column label="Classes" width="270">
                                            <template slot-scope="scope">
                                                <input class="param" type="text" readonly :value='scope.row.classe' />
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="descricao" label="Descrição">
                                        </el-table-column>
                                    </el-table>

                                </div>

                                <div class="field shortcode">

                                    <label style="margin-bottom: 17px">
                                        Exemplos:
                                    </label>

                                    <ul class="details">
                                        <li>
                                            <div class="infos"> <strong> Exemplo 1: </strong> Ao clicar na "div" com as classes abaixo carrega o modal com o tipo de cotação "Saúde". </div>
                                        </li>
                                        <li> <input type="text" readonly="readonly" value='<div class="alink alink-saude"> Cotação Saúde </div>'> </li>
                                        <br>
                                        <li>
                                            <div class="infos"><strong> Exemplo 2: </strong> Ao clicar no "botão" com as classes abaixo carrega o modal com o tipo de cotação "Saúde" e formulário "Individual". </div>
                                        </li>
                                        <li> <input type="text" readonly="readonly" value='<button class="alink alink-saude-individual"> Botão de cotação </button>'>
                                        </li>
                                    </ul>

                                </div>
                            </el-tab-pane>
                            <el-tab-pane label="Formulario de Leads" name="leads">
                                <div class="field shortcode">
                                    <label>
                                        Shortcode do formulário de Leads
                                    </label>
                                    <div class="infos">Com o shortcode destacado abaixo você poderá gerar um formulário de captura de Leads, copie e cole na página onde deseja exibir o formulário:</div>
                                    <div class="copy">
                                        <input type="text" readonly value='[alink-form-pols]' />
                                    </div>
                                    <br>
                                    Veja abaixo todos os parâmetros que podem ser configurados no shortcode.
                                </div>

                                <div class="field short shortcode">
                                    <label style="margin-bottom: 17px">
                                        Parâmetros do shortcode
                                    </label>
                                    <el-table border :data="tableDataShortcode" max-height="380" style="width: 100%" :fit="true">
                                        <el-table-column label="Parâmetro" width="145">
                                            <template slot-scope="scope">
                                                <input class="param2" type="text" readonly :value='scope.row.parametro' />
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Descrição" width="225">
                                            <template slot-scope="scope">
                                                <div v-html="scope.row.descricao"> </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Valores possíveis">
                                            <template slot-scope="scope">
                                                <div v-html="scope.row.values"> </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Padrão" width="130">
                                            <template slot-scope="scope">
                                                <b>
                                                    <div v-if="scope.row.padrao == '#ffffff'" :style="`color: #666`" v-html="scope.row.padrao"></div>
                                                    <div v-else :style="`color: ${scope.row.padrao}`" v-html="scope.row.padrao"></div>
                                                </b>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <label style="margin: 25px 0 17px">
                                        Como aplicar parâmetros no shortcode?
                                    </label>
                                    <div class="infos">Veja no exemplo abaixo como utilizar os parâmetros, você pode editar o shortcode e copiar se preferir: </div>
                                    <div class="ex-shortcode">
                                        <textarea>  [alink-form-pols tipo-cotacao="seguro" formulario="automovel" texto-botao="Enviar mensagem" cor-fundo-botao="#007bff" msg-sucesso="Sucesso!"]  </textarea>
                                    </div>
                                </div>

                            </el-tab-pane>
                        </el-tabs>
                    </div>
                    <div class="alink-footer">
                        <el-row :gutter="20">
                            <el-col class="btn-alink" :xs="24" :span="24">
                                <el-button native-type="submit" @click="updateOptions" type="primary">Salvar alterações</el-button>
                            </el-col>
                        </el-row>
                    </div>
                </form>
            </el-col>
        </el-row>
    </div>

    <script>
        new Vue({
            el: '#agencia-link',
            data() {
                return {
                    activeName: 'inicio',
                    tableData1: [{
                        id: 1,
                        classe: 'alink alink-saude',
                        descricao: 'Carrega modal com tipo de cotação "Saúde".',
                        children: [{
                            id: 31,
                            classe: 'alink alink-saude-individual',
                            descricao: 'Carrega modal com tipo de cotação "Saúde" e formulário "Individual".',
                        }, {
                            id: 32,
                            classe: 'alink alink-saude-empresarial',
                            descricao: 'Carrega modal com tipo de cotação "Saúde" e formulário "Empresarial".',
                        }, {
                            id: 32,
                            classe: 'alink alink-saude-familiar',
                            descricao: 'Carrega modal com tipo de cotação "Saúde" e formulário "Familiar".',
                        }]
                    }, {
                        id: 2,
                        classe: 'alink alink-adesao ',
                        descricao: 'Carrega modal com tipo de cotação "Adesão".',
                        children: [{
                            id: 31,
                            classe: 'alink alink-adesao-individual',
                            descricao: 'Carrega modal com tipo de cotação "Adesão" e formulário "Individual".',
                        }, {
                            id: 32,
                            classe: 'alink alink-adesao-empresarial',
                            descricao: 'Carrega modal com tipo de cotação "Adesão" e formulário "Empresarial".',
                        }, {
                            id: 32,
                            classe: 'alink alink-adesao-familiar',
                            descricao: 'Carrega modal com tipo de cotação "Adesão" e formulário "Familiar".',
                        }]
                    }, {
                        id: 3,
                        classe: 'alink alink-odonto',
                        descricao: 'Carrega modal com tipo de cotação "Odonto".',
                        children: [{
                            id: 31,
                            classe: 'alink alink-odonto-individual',
                            descricao: 'Carrega modal com tipo de cotação "Odonto" e formulário "Individual".',
                        }, {
                            id: 32,
                            classe: 'alink alink-odonto-empresarial',
                            descricao: 'Carrega modal com tipo de cotação "Odonto" e formulário "Empresarial".',
                        }, {
                            id: 32,
                            classe: 'alink alink-odonto-familiar',
                            descricao: 'Carrega modal com tipo de cotação "Odonto" e formulário "Familiar".',
                        }]
                    }, {
                        id: 4,
                        classe: 'alink alink-seguro-vida',
                        descricao: 'Carrega modal com tipo de cotação "Seguro de Vida".',
                        children: [{
                            id: 31,
                            classe: 'alink alink-seguro-automovel',
                            descricao: 'Carrega modal com tipo de cotação "Seguro" e formulário para "Automovéis".',
                        }, {
                            id: 32,
                            classe: 'alink alink-seguro-condominio',
                            descricao: 'Carrega modal com tipo de cotação "Seguro" e formulário para "Condomínios".',
                        }, {
                            id: 32,
                            classe: 'alink alink-seguro-empresa',
                            descricao: 'Carrega modal com tipo de cotação "Seguro" e formulário para "Empresas".',
                        }, {
                            id: 32,
                            classe: 'alink alink-seguro-previdencia',
                            descricao: 'Carrega modal com tipo de cotação "Seguro" e formulário para "Previdência".',
                        }, {
                            id: 32,
                            classe: 'alink alink-seguro-residencial',
                            descricao: 'Carrega modal com tipo de cotação "Seguro" e formulário "Residencial".',
                        }, {
                            id: 32,
                            classe: 'alink alink-seguro-outros',
                            descricao: 'Carrega modal com tipo de cotação "Seguro" e formulário para "Outros" seguros.',
                        }]
                    }, {
                        id: 5,
                        classe: 'alink alink-regiao',
                        descricao: 'Carrega modal com tipo de cotação "Região".',
                    }, {
                        id: 6,
                        classe: 'alink alink-economia',
                        descricao: 'Carrega modal com tipo de cotação "Economia".',
                    }, {
                        id: 7,
                        classe: 'alink alink-rede',
                        descricao: 'Carrega modal com tipo de cotação "Rede".',
                    }, {
                        id: 8,
                        classe: 'alink alink-contato',
                        descricao: 'Carrega modal com formulário de contato.',
                        children: [{
                            id: 31,
                            classe: 'alink alink-contato-ligamos',
                            descricao: 'Carrega modal com formulário de contato. Nossa equipe entrará em contato por telefone.',
                        }]
                    }],
                    tableDataShortcode: [{
                        parametro: 'tipo-cotacao',
                        descricao: 'Controla o tipo de cotação que será exibido no modal após envio bem-sucedido do formulário de Leads.',
                        values: '<b>saude</b> | <b>adesao</b> | <b>odonto</b> | <b>regiao</b> | <b>economia</b> | <b>rede</b> | <b>seguro</b> | <b>contato</b>',
                        padrao: '<b>saude</b>',
                    }, {
                        parametro: 'formulario',
                        descricao: 'Controla qual o tipo de formulário que será exibido. <b><i>OBS: Esta opção só é válida para os tipos de cotação: saude, adesao, odonto, seguro e contato.</i></b>',
                        values: 'Para tipo de cotação <b>saude</b>, <b>adesao</b>, <b>odonto</b> os seguintes valores:  <br><br> <b>individual</b> | <b>familiar</b> | <b>empresarial</b>  <br> <hr> Para <b>seguro</b>:<br> <br> <b>vida</b> | <b>automovel</b> | <b>condominio</b> | <b>empresa</b> | <b>previdencia</b> | <b>residencial</b> | <b>outros</b> <br>    <hr>  Para <b>contato</b>:<br> <br> <b>falamos</b>',
                        padrao: 'opcional',
                    }, {
                        parametro: 'campo-telefone',
                        descricao: 'Controla a exibição do campo "Telefone Fixo" no formulário.',
                        values: '<b>sim</b> ou <b>nao</b>',
                        padrao: '<b>sim</b>',
                    }, {
                        parametro: 'campo-mensagem',
                        descricao: 'Controla a exibição do campo "Mensagem" no formulário.',
                        values: '<b>sim</b> ou <b>nao</b>',
                        padrao: '<b>sim</b>',
                    }, {
                        parametro: 'cor-fundo-campos',
                        descricao: 'Altera a cor de fundo dos campos do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#ffffff'
                    }, {
                        parametro: 'cor-borda-campos',
                        descricao: 'Altera a cor da borda dos campos do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#DCDFE6'
                    }, {
                        parametro: 'cor-fonte-campos',
                        descricao: 'Altera a cor da fonte dos campos do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#333333'
                    }, {
                        parametro: 'cor-label',
                        descricao: 'Altera a cor dos rótulos (labels) dos campos.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#606266'
                    }, {
                        parametro: 'cor-alertas',
                        descricao: 'Altera a cor dos alertas dos campos obrigátórios. A cor é aplicada na borda do campo e no dizer "obrigatório".',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#F56C6C'
                    }, {
                        parametro: 'texto-botao',
                        descricao: 'Altera o texto do botão do formulário.',
                        values: 'escreva seu texto no parâmetro',
                        padrao: 'Enviar'
                    }, {
                        parametro: 'cor-fundo-botao',
                        descricao: 'Altera a cor do botão do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#007bff'
                    }, {
                        parametro: 'cor-fonte-botao',
                        descricao: 'Altera a cor da fonte do botão do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#ffffff'
                    }, {
                        parametro: 'msg-sucesso',
                        descricao: 'Altera o texto da mensagem de sucesso que é exibida ao enviar o formulário.',
                        values: 'escreva sua mensagem no parâmetro',
                        padrao: 'Sua mensagem foi enviada com sucesso! Em breve um de nossos consultores entrará em contato.'
                    }, {
                        parametro: 'cor-msg-sucesso',
                        descricao: 'Altera a cor do texto da mensagem de sucesso.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#007bff'
                    }, {
                        parametro: 'fonte-msg-sucesso',
                        descricao: 'Altera o tamanho da fonte da mensagem de sucesso.',
                        values: 'qualquer valor em pixels',
                        padrao: '14px'
                    }, {
                        parametro: 'espaco-entre-campos',
                        descricao: 'Altera o espaçamento vertical entre os campos do formulário.',
                        values: 'qualquer valor em pixels',
                        padrao: '22px'
                    }, {
                        parametro: 'largura-form',
                        descricao: 'Altera a largura do container do formulário, a largura dos campos também serão afetadas.',
                        values: 'qualquer valor em pixels',
                        padrao: '600px'
                    }, {
                        parametro: 'layout-form',
                        descricao: 'Altera o layout do formulário para uma ou duas colunas.',
                        values: '<b>uma-coluna</b> ou <b>duas-colunas</b>',
                        padrao: '<b>duas-colunas</b>'
                    }, {
                        parametro: 'altura-iframe-desktop',
                        descricao: 'Altera a altura do iframe na versão desktop. <b><i>OBS: Esta opção só é necessária quando inserido um  valor  no parâmetro "espaco-entre-campos" ou for necessário ajustar a altura do iframe.</i></b>',
                        values: 'qualquer valor em pixels',
                        padrao: 'opcional'
                    }, {
                        parametro: 'altura-iframe-mobile',
                        descricao: 'Altera a altura do iframe na versão mobile. <b><i>OBS: Esta opção só é necessária quando inserido um  valor  no parâmetro "espaco-entre-campos" ou for necessário ajustar a altura do iframe.</i></b>',
                        values: 'qualquer valor em pixels',
                        padrao: 'opcional'
                    }]

                }
            },
            methods: {
                openSupport() {
                    window.open("https://suporte.agencialink.com.br/b2cor/plugin-wp-pols");
                }
            }
        });
    </script>

    <style>
        /* DEFAULTS */

        body {
            background: #eeeeee !important
        }

        h1 {
            font-family: 'Merriweather', serif;
            text-align: center;
            font-weight: 900 !important;
            margin: 0;
            font-size: 22px;
            background: #fcfcfc;
            padding: 22px 0 19px;
            border-bottom: 1px solid #e4e7ed;
        }

        h1 font {
            font-size: 12px;
            color: #ccc;
            letter-spacing: -0.3px;
            margin-left: 3px;
        }

        /* CONTAINER */

        #agencia-link {
            padding: 20px 0 0;
        }

        #agencia-link .alink-container {
            background: #FFF;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 1px 2px rgb(0 0 0 / 6%), 0 1px 3px rgb(0 0 0 / 10%);
            width: 800px;
        }

        /* HEAD */

        #agencia-link .alink-container .alink-head {
            padding: 20px 20px 10px;
            border-bottom: 1px solid #eee;
        }

        #agencia-link .alink-container .alink-head img {
            width: 206px;
        }

        #agencia-link .alink-container .alink-head .support {
            text-align: right;
        }

        /* BODY */

        #agencia-link .alink-container .alink-body .welcome {
            background: #007bff;
            text-align: center;
            color: #FFF;
            padding: 20px 20px;
        }

        #agencia-link .alink-container .alink-body .welcome p {
            font-size: 18px;
            margin: 0;
            font-weight: 600;
            font-family: 'Merriweather', serif;
        }

        #agencia-link .alink-container .alink-body .welcome .txt2 {
            font-size: 14px;
            font-weight: 300;
            margin-top: 6px;
        }

        #agencia-link .alink-container .alink-body .field {
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        #agencia-link .alink-container .alink-body .field .infos {
            font-size: 12px;
            background: #f6f6f6;
            padding: 6px 10px;
            border-radius: 3px;
            margin-bottom: 13px;
            color: #666;
        }

        #agencia-link .alink-container .alink-body .field>label {
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
            color: #333;
            font-weight: 700;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .copy {
            text-align: center;
            font-size: 16px;
            padding: 20px 20px;
            background: #fafafa;
            border-radius: 6px;
            color: #999;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .copy blockquote {
            background: #FFF;
            padding: 20px;
            font-weight: 600;
            text-align: center;
            border-color: #eee;
            color: #333;
            border-radius: 6px;
            font-size: 18px;
            letter-spacing: -0.5px;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .copy input {
            width: 60%;
            text-align: center;
            border-color: #eee;
            height: 52px;
            color: #333;
            font-size: 18px;
            letter-spacing: -0.5px;
            font-weight: 600;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .details {
            margin: 0;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .details li {
            margin-top: 10px;
            color: #666;
            font-size: 13px;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .details li strong {
            color: #333;
        }

        #agencia-link .alink-container .alink-body .field input[type=text],
        #agencia-link .alink-container .alink-body .field textarea {
            width: 100%;
            transition: .3s;
            border: 1px solid #ddd;
            outline-width: 0;
            background: #fefefe;
        }

        #agencia-link .alink-container .alink-body .field input[type=text]::placeholder,
        #agencia-link .alink-container .alink-body .field textarea::placeholder {
            font-size: 12px;
            color: #ddd;
        }

        #agencia-link .alink-container .alink-body .field textarea {
            min-height: auto;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            padding: 17px 20px 17px;
            height: 57px;
        }

        #agencia-link .alink-container .alink-body .field.short textarea {
            min-height: auto;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            padding: 17px 20px 17px;
            height: 118px;
        }

        #agencia-link .alink-container .alink-body .field input[type=text]:focus,
        #agencia-link .alink-container .alink-body .field textarea:focus,
        #agencia-link .alink-container .alink-body .field input[type=text]:active,
        #agencia-link .alink-container .alink-body .field textarea:active {
            border: 1px solid #007bff;
            background: #fafafa;
        }

        /* TABS */

        #agencia-link .alink-container .alink-body .el-tabs__header {
            padding: 20px 20px 0 20px !important;
            margin: 0 !important;
        }

        /* TABELA */

        #agencia-link .alink-container .alink-body .el-table__header-wrapper {
            font-size: 12px;
        }

        #agencia-link .alink-container .alink-body .el-table .cell {
            line-height: 18px;
            font-size: 12px;
            word-break: break-word !important;
            font-family: "Open Sans";
        }

        #agencia-link .alink-container .alink-body .el-table input.param {
            border: 0 !important;
            padding: 0 3px;
            width: 80% !important;
            font-size: 12px !important;
            box-shadow: none !important;
            font-weight: 600;
        }

        #agencia-link .alink-container .alink-body .el-table input.param2 {
            border: 0 !important;
            padding: 0 3px;
            width: 98% !important;
            font-size: 12px !important;
            box-shadow: none !important;
            font-weight: 600;
        }

        #agencia-link .alink-container .alink-body .el-table input.param:focus {
            border: 0 !important;
            outline-width: 0;
        }

        #agencia-link .alink-container .alink-body .el-table thead {
            color: #000000;
            font-weight: 500;
        }

        #agencia-link .alink-container .alink-body .el-table td,
        #agencia-link .alink-container .alink-body .el-table th {
            padding: 7px 0;
        }

        /* FOOTER */

        #agencia-link .alink-container .alink-footer {
            padding: 20px;
        }

        #agencia-link .alink-container .alink-footer .btn-alink {
            text-align: right;
        }

        #agencia-link .alink-container .alink-footer .version {
            font-weight: 600;
            color: #ddd;
            margin-top: 12px;
        }


        @media only screen and (min-device-width : 0px) and (max-width : 980px) {
            #agencia-link .alink-container {
                width: 100%;
            }
        }

        @media only screen and (min-device-width : 0px) and (max-width : 760px) {

            #agencia-link .alink-container .alink-head .logo-pols {
                display: none;
            }
        }
    </style>

<?php
}
