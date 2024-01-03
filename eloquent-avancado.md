## Eloquent Avançado

## Populando Tabelas

No Laravel, para popular tabelas no banco de dados usando Seeders, podemos utilizar o arquivo `DatabaseSeeder.php`. No método `run`, podemos adicionar registros usando comandos como:

```php
\DB::table('users')->insert([
    'name' => 'joao',
    'email' => 'joao@teste.com',
    'password' => bcrypt('1234')
])

\App\User::create([
    'name' => 'joaooutro',
    'email' => 'joaooutro@teste.com',
    'password' => bcrypt('1234')
])

```
## Utilizando Seeders

Executamos o comando:
```php
php artisan db:seed
```
para inserir os dados no banco de dados. No entanto, existem maneiras mais eficientes, como a criação de Seeders específicos.

## Criando um Novo Seeder
Para criar um novo Seeder pelo artisan, utilizamos o comando:

```php
php artisan make:seeder UsersTableSeeder

```

O Laravel cria o arquivo dentro da pasta database/seeds. Podemos usá-lo no método run() do DatabaseSeeder usando:

```php
$this->call(UsersTableSeeder::class);
```

## Usando Factory para Dados Falsos
O Laravel fornece Factory para criar dados falsos de maneira mais profissional. A pasta database/factories contém arquivos como UserFactory.php. Para usar um Factory, dentro do UsersTableSeeder:

```php
factory(App\User::class)->make();
```

## Executando Seeders Específicos
Para executar um Seeder específico usando o artisan, usamos o comando:

```php
php artisan db:seed --class=UsersTableSeeder
```

## Utilizando o Faker
O arquivo config/app.php possui o comando 'faker_locale' => 'en_US', substituir por 'pt_BR'. 

Podemos criar múltiplos registros facilmente usando o Factory:
```php
factory(App\User::class, 20)->create();
```

## Zerando todos os dados do banco com migrate
Ao criar todos os dados percebemos que se formos criar uma nova tabela 

```php
php artisan migrate:refresh 
```


## Criando nova Factory
Iremos criar uma nova factory para a tabela de categorias de POSTs, e logo em seguida um novo seed para implementar o Factory

```php
php artisan make:factory CategoryFactory --model=Category
php artisan make:seeder CategoriesTableSeeder
```

Com o "--model=Category" definimos o Model que ele irá consumir na Factory

O código dentro do CategoryFactory.php fica mais ou menos assim:

```php
use Faker\Generator as Faker;
$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name'=>$faker->text(15),
        'description'=>$faker->text(200)
    ];
});
```
* No CategoriesTableSeeder usamos referência do factory que criamos:

```php
factory(App\Category::class, 20)->create();
```
Por fim, utilizamos o código para criar os seeds e preencher os dados da nossa tabela

```php
php artisan db:seed --class=CategoriesTableSeeder
```

## Conclusão
Usar Seeders e Factories no Laravel permite a criação e inserção de dados de maneira eficiente, facilitando a geração de registros falsos e o preenchimento inicial do banco de dados.