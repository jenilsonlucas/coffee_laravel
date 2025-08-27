<h1 align="center">Coffee Code App</h1>

<p align="center">
Monitorize receitas e despesas em tempo real
</p>

## SOBRE A APLICAÇÃO

Este aplicativo foi criado para ajudar os usuários a gerenciar suas finanças pessoais, controlando tanto as despesas quanto as receitas. Além disso, inclui uma seção de blog com conteúdos úteis e informações sobre a empresa. O objetivo do projeto é apoiar pessoas que têm dificuldade em acompanhar suas atividades financeiras.

A aplicação foi desenvolvida utilizando Docker, Laravel, JavaScript, HTML e CSS. O PHPUnit foi usado para implementar testes automatizados, e pipelines de CI/CD foram configurados para garantir um processo de implantação contínuo e eficiente.

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

- Configure as variaveis do arquivo .env 

- Suba os containers **docker compose up --build**

- Abra o navegador e coloque na url **http://localhost:8080**
