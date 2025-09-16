<h1 align="center">Coffee Code App</h1>

<p align="center">
Monitorize receitas e despesas em tempo real
</p>

## SOBRE A APLICAÇÃO

Este aplicativo foi criado para ajudar os usuários a gerenciar suas finanças pessoais, controlando tanto as despesas quanto as receitas. Além disso, inclui uma seção de blog com conteúdos úteis e informações sobre a empresa. O objetivo do projeto é apoiar pessoas que têm dificuldade em acompanhar suas atividades financeiras.

A aplicação foi desenvolvida utilizando Docker, Laravel, JavaScript, HTML e CSS. O PHPUnit foi usado para implementar testes automatizados, e pipelines de CI/CD foram configurados para garantir um processo de implantação contínuo e eficiente.

O aplicativo também conta com recursos como eventos e listeners, envio de email, middlware, seeders, factores, entros recursos. Espero que analísem e desfrutem do projecto. 

<h4>Directórios para veres</h4>

- app/Http/Controllers/
- app/Listeners/
- app/Models/
- app/Providers/
- app/Support/
- database/factories/
- database/migrations/
- database/seeders/
- docker/
- public/
- resources/
- routes/
- tests/Feature

Análise cada arquivo destes directórios para poderes entender melhor como funciona o projecto e como ele foi construido

## INSTALAÇÃO DO PROJECTO

### REQUISITOS

Para poder instalar e rodar o projecto em sua maquina local precisas ter certos aplicativos instalados e configurados em sua maquina 

Obs: Baixa consoante o teu sistema operativo, aqui eu vou deixar links para instalar em sistemas operativos linux.

- [GIT](https://git-scm.com/downloads/linux)
- [DOCKER](https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository)

### PROCESSO DE INSTALAÇÃO

- Clone o projecto em sua maquina local **git clone https://github.com/jenilsonlucas/coffee_laravel.git**.
 
- Entra na pasta do projecto **cd coffee_laravel**

- Copia o arquivo .env.example para .env **cp .env.example .env**
  configure as variaveis do banco de dados para poder fazer a migrate e popular o banco de dados com seeds;
  também configure as variavéis ambiente para email, existe serviços gratis como o google.

- Suba os containers **docker compose up --build -d**
  obs: verifique se a variavel de ambiente APP_ENV está local no arquivo .env
- Abra o navegador e coloque na url **http://localhost:8081**

## AMBIENTE DE TEST

Para executares os testes desenvolvidos para a aplicação, primeiro abre o arquivo .env e modifica a variavel de ambiente APP_ENV para testing,
pare os containers e volte a subir **docker compose down && docker compose up -d**, depois disso vai ate a linha de terminal e rode o comando **docker exec -ti laravel_app bash**, isso vai te levar para dentro do terminal do container, depois disso rode o comando **php artisan test**.

## AMBIENTE DE PRODUÇÃO

Obs: Por estar utilizando um serviço de hospedagem gratuito, please espera uns 5 segundos para que o projecto possa abrir·

Para veres como o projecto se encontra em produção clica neste link [coffee_code](https://coffee-code.onrender.com).

## PLANEJAMENTO DE CONTINUIDADE

Para a continuidade do projecto, vai ser instalados algumas novas features com o CRUD de categorias das dispesas e receitas, o CRUD de carteiras, consumir um api de pagamentos para sistema poder se adaptar dependentemente do plano do usuario, e mais alguns outros recursos

## COLABORADORES

Espera - se que os colaboradores do projecto possam encontrar ajudar a encontrar errors, tanto de segurança como de desempenho entre outros, possam fazer a adição de novas features, tais com a que estão em cima referidas como outras que possais pensas


## **Autor: Jenilson Domingos Da Costa Lucas**
