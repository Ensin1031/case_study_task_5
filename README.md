УНИВЕРСИТЕТ «СИНЕРГИЯ»              
Факультет Интернет-профессий                    
Направление подготовки /специальность: 09.03.02 Информационные системы и технологии              
Профиль/специализация:  Веб-разработка              
Форма обучения:   Заочная            

Производственная практика                 
Кейс-задача № 5  "Дневник путешествий"           

Для запуска проекта необходимо:          
- установить [php](https://www.php.net/), [compocer](https://getcomposer.org/), [laravel](https://laravel.com/), [docker](https://www.docker.com/)
- скачать содержимое git-репозитория                 
```php
git clone https://github.com/Ensin1031/case_study_task_5.git
```
- перейти в корневую папку проекта      
```php
cd /case_study_task_5
```
- развернуть проект в докере
```php
sudo docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```                 
```php
npm install && npm run build
```                
```php
php artisan key:generate
```            
```php
php artisan migrate
```            
- запустить проект      
```php
composer run dev
```                
