<h3>Requisitos</h3>
<ul>
<li>PHP 8.2</li>
<li>Extensão ZipArchive ativa no php</li>
<li>Composer 2.5.8 ou maior</li>
</ul>
<h3>Instalação</h3>
<ol>
<li>Faça o download do projeto presumindo que já tenha instalado o git em sua maquina digitando o comando <b>git clone git@github.com:luanmarcosfgns/backup.git</b>, caso contrário baixe como zip. </li>
<li>Abra o terminal e vá ao diretório que está o seu projeto como no exemplo. <b>cd /seu/diretorio/aqui</b>  </li>
<li>Digite <b>composer install</b> no terminal, verificando estar sobre seu projeto </li>
<li>preencha o env
<br>
#########################################
<ul>
<li><h3>CREDÊNCIAIS DE BANCO DADOS PARA BACKUP</h3></li>
<li>USERNAME="root" </li>
<li>PASSWORD="root"</li>
<li>HOST="localhost"</li>
<li>BACKUP_DATABASE="'harp','gestor','chatbot'"</li>

<li><h3>DIRETÓRIOS PARA BACKUP  </h3></li>
<li>BACKUP_DIRECTORIES="/home/luanmarcos/Imagens,/home/luanmarcos/Downloads"</li>

<li><h3>MEMORIA MAXIMA A SER ATINGIDA PELO SCRIPT</h3></li>
<li>MEMORY_LIMIT="4000M"</li>

<li><h3>CREDENCIAIS DO GOOGLE DRIVE</h3></li>
<li>SHARE_GOOGLE_DRIVE="YES"</li>
<li>SHARE_EMAIL="example@gmail.com"</li>
</ul>
########################################################
</li>
<li>Caso queira sincronizar com o google você deve acessar <a href="https://console.cloud.google.com/">console.cloud.google.com</a> e faça o download das credenciais de serviço substituindo o arquivo credentials.json e localize o atributo SHARE_GOOGLE_DRIVE do arquivo .env e coloque como "YES"  
do mesmo arquivo</li>
<li>Logo após compartilhe seu email do google drive através do atributo  SHARE_EMAIL do mesmo arquivo .env </li>
<li>Sobre a execução ela pode ser feita de duas formas digitando <b>php -S localhost:8000</b> acessando o mesmo pelo navegador através da URL <b>http://localhost:8000/backup-bd.php </b> ou você pode execultar diretamente via comando: <b>php backup-bd.php</b></li>
<li>E POR FIM FORTEMENTE RECOMENDO QUE NÃO UTILIZE O APLICATIVO A CÉU ABERTO, OU SEJA, EM REQUISIÇÕES HTTP REMOTAS PUBLICAS OU DE OUTRA FORMA QUE NÃO VENHA TOMAR AS DEVIDAS PRECAUÇÕES POIS O MESMO NÃO POSSUI RESTRIÇÕES DE SEGURANÇA E FORA PENSADO PARA RODAR COMO SERVIÇO OU JOB DE QUALQUER SISTEMA OPERACIONAL</li>
</ol>