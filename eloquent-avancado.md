<center>

# Eloquent Avançado

</center>

<span style="font-size:30px;font-weight:bold">Seeders</span>

### Populando Tabelas

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
### Utilizando Seeders

Executamos o comando:
```php
php artisan db:seed
```
para inserir os dados no banco de dados. No entanto, existem maneiras mais eficientes, como a criação de Seeders específicos.

### Criando um Novo Seeder
Para criar um novo Seeder pelo artisan, utilizamos o comando:

```php
php artisan make:seeder UsersTableSeeder

```

O Laravel cria o arquivo dentro da pasta `database/seeds`. Podemos usá-lo no método `run()` do DatabaseSeeder usando:

```php
$this->call(UsersTableSeeder::class);
```

### Usando Factory para Dados Falsos
O Laravel fornece Factory para criar dados falsos de maneira mais profissional. A pasta `database/factories` contém arquivos como `UserFactory.php`. Para usar um Factory, dentro do UsersTableSeeder:

```php
factory(App\User::class)->make();
```

#### Executando Seeders Específicos
Para executar um Seeder específico usando o artisan, usamos o comando:

```php
php artisan db:seed --class=UsersTableSeeder
```

### Utilizando o Faker
O arquivo `config/app.php` possui o comando `'faker_locale' => 'en_US'`, substituir por 'pt_BR'. 

Podemos criar múltiplos registros facilmente usando o Factory:
```php
factory(App\User::class, 20)->create();
```

### Zerando todos os dados do banco com migrate
Ao criar todos os dados percebemos que se formos criar uma nova tabela 

```php
php artisan migrate:refresh 
```


### Populando dados da tabela `categories`
Iremos criar uma nova factory para a tabela de categorias de POSTs, e logo em seguida um novo seed para implementar o Factory

```php
php artisan make:factory CategoryFactory --model=Category
php artisan make:seeder CategoriesTableSeeder
```

Com o `--model=Category` definimos o Model que ele irá consumir na Factory

O código dentro do `CategoryFactory.php` fica mais ou menos assim:

```php
use Faker\Generator as Faker;
$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name'=>$faker->text(15),
        'description'=>$faker->text(200)
    ];
});
```
No `CategoriesTableSeeder` usamos referência do factory que criamos:

```php
factory(App\Category::class, 20)->create();
```
Por fim, utilizamos o código para criar os seeds e preencher os dados da nossa tabela

```php
php artisan db:seed --class=CategoriesTableSeeder
```


### Populando dados da tabela `posts`

Iremos criar uma nova factory para a tabela de POSTs, e logo em seguida um novo seed para implementar o Factory

```php
php artisan make:factory CommentFactory --model=Post
php artisan make:seeder CommentsTableSeeder
php artisan make:factory PostFactory --model=Post
php artisan make:seeder PostsTableSeeder
```

O `CommenFactory.php` ficardessa forma:

```php
$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'title'=>$faker->text(15),
        'content'=>$faker->text(200),
        'post_id'=>function(){
            return factory(App\post::class)->create()->id;
        }
    ];
});
```

O `PostFactory.php` ficará assim:

```php
use Faker\Generator as Faker;
$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title'=>$faker->text(15),
        'content'=>$faker->text(200),
    ];
});
```
No `PostsTableSeeder` usamos referência do factory que criamos:

```php
factory(App\Comments::class)->create();
```


Caso ao executar algum desses comandos e constar que o arquivo não existe, provavelmente pode ser por causa do cache que é guardado no Laravel e ele pode não ter pegado as últimas alterações. Para solucionar isso utilizamos o comando:

```php
composer dump-autoload
```


Para a factory `PostFactory` iremos criar alguns métodos dentro dela, embora ela não tenha chave estrangeira, ela possui referências em outras tabelas.

Adicionaremos o método de detalhes do post no factory:

```php
use Faker\Generator as Faker;
$factory->define(App\Details::class, function (Faker $faker) {
    return [
        'status'=>$faker->randomElement(['publicado','rascunho']),
        'visibility'=>$faker->randomElement(['publico','privado']),
    ];
});

```

Criaremos o método `run` do `PostsTableSeeder`:

```php
factory(App\Post::class)->create()->each(function($post) {
    $post->comments()->savemany(
        factory(App\Comment::class,3)->make([
            'post_id'=>$post->id
        ])
    );
    
    $post->categories()->save(
        factory(App\Category::class)->make()
    );
    
    $post->details()->save(
        factory(App\Details::class)->make()
    );
});

// Estamos definindo que para cada Post novo ele irá adicionar 3 comentários, 1 detalhe e 1 categoria.
// Passando o array no método make do Comment para que os comentários não acabem gerando 
// um post cada. E então ele irá conseguir referenciar a apenas o post que está sendo criado.
```

Por fim, utilizamos o código para criar os seeds e preencher os dados da nossa tabela

```php
php artisan db:seed --class=PostsTableSeeder
```

No `DatabaseSeeder` no método `run` iremos fazer umas alterações para adicionar as novas classes que criamos.


Ele estava assim, até então:
```php
    $this->call(UsersTableSeeder::class);
```

Como adicionaremos mais de uma classe, usaremos array para envolver eles:
```php
    $this->call([
        CategoriesTableSeeder::class,
        CommentsTableSeeder::class,
        PostsTableSeeder::class,
        UsersTableSeeder::class
    ]);
```

Limparemos novamente o banco de dados para adicionar novos dados:

```php
php artisan migrate:refresh
```

Agora podemos usar o comando para popular todos os seeders de uma vez:
```php
php artisan db:seed
```


<span style="font-size:30px;font-weight:bold;">Relações Polimórficas</span>


Iremos criar uma tabela de avaliações.
Ao pensar conseguimos ver o quão complexo poder ser, pois essa tabela identificará para quem é a 
avaliação no sentido de se a avaliação será para comentário, para o post ou para um usuário.
Existem N maneiras de fazer essa tarefa, criando por exemplo uma tabela que tenha o id de quem está sendo avaliado e os campos de todos que podem ser avaliados (A desvantagem seria deixar muitos campos vazios), nesse caso, usaremos a seguinte forma: 

<b>Criaremos uma tabela com nome `ratings`</b>, que guardará todas as avaliações, seja de usuários ou posts, 
o id do referenciado (se é o id do post ou o id do user) e qual o tipo do avaliado (se é post ou user).

Iremos criar o model `Rating` com:

```php
php artisan make:model Rating
```

Nossa model ficará assim precisa implementar um método no model para dizer que retorna uma tabela.
Usaremos o `morphTo`, pois ele terá apenas uma avaliação.
Ficará assim:
```php
class Rating extends Model
{
   public function ratingable()
   {
       return $this->morphTo();
   }
}
```

No model `Post` será adicionado várias avaliações para cada post.
Então será adicionado mais um método no model `Post` com o método `morphMany`, passando como parâmetro o model que ele fará referência, dessa forma:
```php
 public function ratings()
{
   return $this->morphMany('App\Model\Rating','ratingable');
}
```

Semelhantemente faremos no model `User`, pois ele faz referência a tabela de avaliações: 
```php
public function ratings()
{
   return $this->morphMany('App\Model\Rating','ratingable');
}
```
### Populando tabela com tinker

Criaremos o primeiro dado a fim de teste com o Tinker:

```php
php artisan tinker
```

Você poderá usar o banco de dados diretamente no console.
Com o comando:
```php
$post = App\Model\Post::find(1)
```
Ele irá me retornar o seguinte:
```php
    App\Model\Post {#2920
        id:1,
        title:"Sed. ",
        content: "Lorem ipsum...",
        created_at: "2018-12-06 14:00:00",
        updated_at: "2018-12-06 16:00:00"
    }
```

Agora quero criar uma avaliação para esse post, nós atribuímos a variável `$post` o valor desse Post com `id = 1`.
<br>Então usando ele podemos criar uma nova avaliação, dessa forma:

```php
$post->ratings()->create(['value'=>9])
```
Criamos uma avaliação com nota 9 para esse post, ao digitarmos esse comando ele nos retornará isso:

```php
App\Model\Rating {#2913
    value:9,
    ratingable_id:1,
    ratingable_type:"App\Model\Post",
    created_at:"2018-12-08 01:00:36",
    updated_at:"2018-12-08 01:00:36",
    id: 6
}
```

Para buscar as avaliações do post:

```php
$post->ratings
```


Semelhantemente faremos com o usuário:


```php
$user = App\Model\User::find(1)
```
Ele irá me retornar o seguinte:
```php
    App\Model\User {#2920
        id:1,
        title:"Sid. ",
        content: "Lorim epsum...",
        created_at: "2018-12-06 11:00:00",
        updated_at: "2018-12-06 12:00:00"
    }
```

```php
$user->ratings()->create(['value'=>6])
```
Criamos uma avaliação com nota 6 para esse usuario, ao digitarmos esse comando ele nos retornará isso:

```php
App\Model\Rating {#2913
    value:6,
    ratingable_id:1,
    ratingable_type:"App\Model\Post",
    created_at:"2018-12-08 01:00:36",
    updated_at:"2018-12-08 01:00:36",
    id: 7
}
```
### Avaliações

Para olharmos as avaliações usamos:

```php
$rating = App\model\Rating::find(7)
```


Que me retornará:

```php
App\Rating{#2932
    value:6,
    ratingable_id:1,
    ratingable_type:"App\Model\Post",
    created_at:"2018-12-08 01:00:36",
    updated_at:"2018-12-08 01:00:36",
    id: 7
}
```

Para olhar ao que ela se referencia, usamos `$rating->ratingable`, que irá retornar:

```php
    App\Model\User {#2933
        id:1,
        title:"Sid. ",
        content: "Lorim epsum...",
        created_at: "2018-12-06 11:00:00",
        updated_at: "2018-12-06 12:00:00"
    }
```


<span style="font-size:30px;font-weight:bold;margin:10px">Trabalhando com tabela pivot</span>

### Como usar o método `pivot`

Para utilizar o método `pivot`, usamos `->pivot` na tabela que está se relacionando com a tabela que estamos utilizando

Por exemplo:

```php
    $post = \App\Model\Post::find(1);
```
Aqui ele retornará o Post com id 1. Iremos acessar as categorias e após acessar o `->pivot()`

### Timestamp ao criar dado

Ao criar algum dado com  o método `create` do Eloquent, utilizando tinker, o timestamp sempre retornava null.

Mas usando o método `->withTimestamp()` no retorno do Model, ele sempre retornará o timestamp.


### Criando novo campo na tabela PIVOT

Ao criar um novo campo na tabela pivot, o tinker ou laravel não reconhecem esse campo por padrão.
Para esse campo ser reconhecido pelo Laravel utilizamos o método `->withPivot('username')` e passamos qual o campo que queremos utilizar.


### Renomeando a relação PIVOT

Por padrão, acessamos a tabela pivot de uma tabela com o método `->pivot`. Porém esse nome é muito geral, e podemos querer especificar o nome da relação.
Para conseguirmos renomear, iremos acessar o model da tabela que queremos renomear a relação.

```php
    return belongTo()->as('relacao');
```


<span style="font-size:30px;font-weight:bold;margin:10px">Agregação e contagem</span>

### Métodos de agregação

Para prosseguir nesse curso iremos utilizar o `tinker` novamente.

```php 
php artisan tinker
```

### Método COUNT
O primeiro método que vamos utilizar será para fazer a contagem de quantos registros temos na tabela que iremos consumir, que nesse caso será a tabela de Avaliações, para isso utilizaremos método o `count`:
```php
App\Model\Rating::count();
```

### Método SUM
Ele retornará: `8`. Pois temos 8 registros na tabela.

O segundo método que vamos utilizar será para fazer a soma dos registros, utilizaremos método o `sum`:
```php
App\Model\Rating::sum('value');
```
Diferentemente do `count` que não precisamos informar o campo, pois ele conta o nº de linhas, iremos aqui informar esse parâmetro apenas para especificar em qual campo queremos fazer a soma.
Ele retornará: `53`.

### Método AVG
O terceiro método que vamos utilizar será para fazer a média dos registros, utilizaremos método o `avg`:
```php
App\Model\Rating::avg('value')
```

Ele retornará: `6.625`.


### Método MIN
O quarto método que vamos utilizar será para trazer o menor registro do campo `value` da tabela `ratings`, para fazer isso utilizaremos método o `min`:
```php
App\Model\Rating::min('value')
```

Ele retornará: `0`. Pois é o menor valor individual de alguma das avaliações



### Método MIN
O quinto método que vamos utilizar será para trazer o maior registro do campo `value` da tabela `ratings`, para fazer isso utilizaremos método o `max`:
```php
App\Model\Rating::max('value')
```

Ele retornará: `10`. Pois é o maior valor individual de alguma das avaliações

### Métodos de agregação com WHERE

Podemos usar a cláusula WHERE e criar querys mais complexas 

Iremos pegar no primeiro momento apenas a média das avaliações do campo `posts`:
```php
App\Model\Rating::where('ratingable_type','App\Post')->avg('value');
```


Agora vamos pegar a média das avaliações do campo `users`:
```php
App\Model\Rating::where('ratingable_type','App\User')->avg('value');
```


Agora vamos pegar o valor mínimo das avaliações do campo `posts`:
```php
App\Model\Rating::where('ratingable_type','App\Post')->min('value');
```

Agora vamos pegar o valor mínimo das avaliações do campo `users`:
```php
App\Model\Rating::where('ratingable_type','App\User')->min('value');
```


### Método de agregação via relação

Podemos usar métodos de agregação via relações da tabela.
```php
$post = App\Model\Post::find(1)

$post->rating()->avg('value')
$post->rating()->sum('value')
$post->rating()->min('value')
$post->rating()->max('value')
```

### Eager Load para contagem

Vamos exibir o número de comentários no nosso projeto. Se formos colocar da maneira que estudamos pode não ser uma solução viável, pois em um projeto grande, como muitos posts e muitos comentários, provavelmente iria demorar muito para carregar e o aplicativo ficaria bem pesado.

Para isso utilizaremos uma biblioteca que nos ajudará a observar o que está acontecendo por baixo dos panos.

Use o comando a seguir para baixar a biblioteca:
```php
composer require barryvdh/laravel-debugbar --dev
```

Na página de lista de posts onde está sendo chamado vamos fazer o primeiro teste de performance. Como a página de posts já utiliza a instância de posts, iremos aproveitar

```php
posts->comments()->count()
```

No Debugbar é possível ver que ele faz uma consulta para cada posts, isso em um projeto grande causaria um grande problema.

Para nossa sorte o Laravel já possui métodos que cuidam disso, utilizaremos o `withCount()`

No `PostController.php`, no método `index`, iremos adicionar o método que queremos para fazer o Eager Load:

```php
    $posts = Post::orderBy('created_at','desc')
                ->whereHas('details',function($query){
                    $query->where('status','publicado')
                    ->where('visibility', 'publico');
                })
                ->withCount('comments')
                ->paginate(10);
```

E agora no arquivo de listagem de posts `post.blade.php`, iremos substituir o
```php
posts->comments()->count()
```
Por:
```php
posts->comments_count
```

O Laravel cria uma variável dinâmica e faz apenas uma consulta para todos os dados em uma consulta apenas. O nome da variável varia de acordo com o nome do método criado para acessar a relação.



<span style="font-size:30px;font-weight:bold;margin:10px">Soft Delete</span>

### Como utilizar

Primeiramente criamos um arquivo nas migrations para fazer a alteração na tabela que iremos utilizar de exemplo.

```php
php artisan make:migration PostAlterTable
```

Como o título lembra, estamos fazer uma alteração no Post. Dentro da migration criada, ficará assim:

```php
    Schema::table('post', function(Blueprint $table){
        $table->softDeletes();
    });
```

Dentro do model Post iremos fazer algumas alterações.
Vamos importar o SoftDeletes
```php
use Illuminate\Database\Eloquent\SoftDeletes;
```

Dentro da classe Post iremos declarar que estamos chamando ele;
```php
use SoftDeletes;
```


Iremos também declarar qual o campo da tabela está sendo alterado :

```php
protected $date = ['deleted_at'];
```

Para visualizar todos arquivos, inclusive os da lixeira, vamos usar o tinker:

```php
php artisan tinker
```

E usamos o comando:
```php
App\Model\Post::withTrashed()->get()
```

Podemos filtrar ainda mais a pesquisar o arquivos da lixeira:

```php
App\Model\Post::withTrashed()->find(3)
```

E ainda podemos saber em forma de boleano se o arquivo foi excluído:

```php
App\Model\Post::withTrashed()->find(3)->trashed()
```

Agora para conseguir visualizar apenas os arquivos excluídos, utilizamos o método `onlyTrashed()` dessa forma:

```php
App\Model\Post::onlyTrashed()
```

E também podemos restaurar algum arquivo com o método `restore()`

```php
App\Model\Post::onlyTrashed()->find(2)->restore()
```




<span style="font-size:30px;font-weight:bold;margin:10px">Escopos</span>


Nesta aula iremos entender como funcionam os escopos.


Para começar iremos criar uma nova migration

```php
php artisan make:migration alter_post_table_add_approved
```

Nessa nova coluna que adicionamos, iremos escrever assim:

```php
Schema::table('posts',function(Blueprint $table){
    $table->integer('approved')->after('content');
})
```

O método after funciona especificamente para o mysql, onde é possivel escolher onde ficará uma coluna recém criada.


Agora vamos rodar a migration que criamos:
```php
php artisan migrate
```


### Como encontrar os arquivos aprovados
Usando métodos, convencionais chamaríamos assim:
```php
App\Model\Post::where('approved',1)->get()
```

Mas existe uma forma simples de fazer isso
Nessa forma vamos usar o escopo local para criar o método completo
```php
App\Model\Post::IsApproved()->get()
```
Criada essa função, vamos utilizar um método na class `Post`

```php
public function scopeIsApproved($query)
{
    return $query->where('approved',1);
}
```

Podemos também criar um método que retorne de forma dinâmica outros tipos de aprovados.

```php
public function scopeApproved($query,$approved)
{
    return $query->where('approved',$approved);
}
```


Chamamos ele dessa forma no tinker:
```php
App\Model\Post::approved()->get(1)
```


### Relações dentro do escopo

Utilizaremos nesta sessão relações dentro de escopo

```php
public function scopeHasCategories($query)
{
    return $query->whereHas('categories')
}
```

Vamos testar com o tinker

```php
php artisan tinker
```

Ao acessar o método com o tinker: 
```php
$post = App\Model\Post::hasCategories()->get()
```

Ele irá retornar apenas as categorias que estão ligadas aos posts

### Escopo global


Até agora utilizamos apenas escopo local
Para utilizar o escopo global precisamos sobrescrever o método boot:
```php
protected static function boot()
{
    parent::boot()

    static::addGlobalScope('orderByCreatedAt', function(Builder $builder){
        $builder->orderBy('created_at', 'desc');
    });
}
```

Vamos testar no tinker:
```php
App\Model\Post::get()
```
Ele trouxe todos os campos ordenados. Então funcionou, ele está obedecendo o escopo global!

#### Ignorar o escopo global

Para ignorarmos o escopo global utilizamos o método

```php
App\Model\Post::withoutGlobalScope('orderByCreatedAt')->get()
```

Este caso ignoramos apenas o escopo global que criamos. Existe uma forma de ignorar todos os escopos globais do model:

```php
App\Model\Post::withoutGlobalScopes('')->get()
```

### Classe propria para escopos

Mesmo sendo poucos escopos criados, não é uma boa prática utilizar os escopos diretamente nos Models, por isso vamos criar classes próprias para eles.

Vamos criar a pasta no caminho `App\Scopes\VisibleScope.php`

```php
<?php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VisibleScope implements Scope {
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('details',function($query){
            $query->where('status','publicado')
                ->where('visibility','publico');
        })
    }
}
?>
```

e chamamos ele no `Post.php` dentro do método boot()

```php
use App\Scopes\VisibleScope;

...

static::addGlobalScope(new VisibleScope);
```


<span style="font-size:30px;font-weight:bold;margin:10px">Eventos e Observers</span>


No Laravel é possível criarmos eventos após alguma ação, por exemplo, após criar um campo, após atualizar e etc.

Vamos colocar mão na massa!

### Executando ações com events e listeners


Iremos utilizar o Model `Post.php`.

Criaremos um array dessa forma:
```php
    protected $dispatchesEvents = [
        'created'=>PostCreated::class
     ]
```

Para criar eventos devemos ir no caminho `App\Providers\EventServiceProvider.php` e acessar a variável `$listen`.
Nessa variável iremos incluir mais uma lógica nela:
```php
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\PostCreated'=> [
            'App\Listeners\PublishOnFacebook',
            'App\Listeners\PublishOnTwitter'
        ]
    ]
```
Para criar evento no tinker:
```php
php artisan event:generate
```

Dentro do `App\` ele criou o arquivo no diretório `Events\PostCreated.php`, e também `Listeners\PublishOnFacebook` e `Listeners\PublishOnTwitter` 

Está faltando agora implementar a lógica dos listeners.

### Lógica dos Listeners

Iremos criar nossa lógica dentro do método `handle` do `PublishOnFacebook`
```php
public function handle(PostCreated $event)
{
    \Log::debug('Publicar no facebook');
}
```

E também dentro do método `handle` do `PublishOnTwitter`
```php
public function handle(PostCreated $event)
{
    \Log::debug('Publicar no twitter');
}
```


Ao usar create no POST ele irá criar os logs em `App\Storage\Logs` e abrir o último log do dia.

Graças ao parâmetro $event passado dentro dos métodos `Listeners\PublishOnFacebook` e `Listeners\PublishOnTwitter`, conseguimos acessar os dados individuais do post criado.

```php
// PublishOnTwitter
public function handle(PostCreated $event)
{
    \Log::debug('O Post '. $event->post->id .' foi publicado no twitter');
}

// PublishOnFacebook
public function handle(PostCreated $event)
{
    \Log::debug('O Post '. $event->post->id .' foi publicado no facebook');
}

```


### Observer

Podemos criar uma classe no Laravel que é o Observer que será executado quando tiver o mesmo nome do Model a quem ele se refere.

Vamos utilizar o `artisan` para criar esse observer

```php
php artisan make:observer PostObserver --model=\Model\Post
```

Ele traz consigo alguns métodos como `created`, `updated`, `deleted`, pois ele adiciona um gatilho no model Post

Em `App\Providers\AppServiceProvider.php` dentro do método `boot()` irei adicionar:


```php
\App\Post::observe(\App\Observers\PostObserver::class);
```

No método `created` e `deleted` do caminho `\App\Observers\PostObserver` iremos criar o seguinte:

```php
\Log::debug('foi deletado/criado com sucesso');
```



<span style="font-size:30px;font-weight:bold;margin:10px">Acessors e Mutators</span>


### Acessores

Para exemplificar os acessores vamos utilizar nossa página de post do blog. Iremos limitar o conteúdo que aparece no inicio do blog, para isso iremos utilizar o campo `content` da nossa tabela `posts`.<br>
No model `Post` criaremos um método novo.

```php
public function getContentAttribute($value)
{
    return mb_strmwidth($value, 0, 30, "...");
}
```

Ao realizar essa modificação quando atualizamos a página e deu certo... Porém ao entrar no post específico ele também mostra apenas os 30 caracteres. Vamos resolver isso!

Iremos comentar o código acima, e iremos criar um novo método:

```php
// public function getContentAttribute($value)
// {
//     return mb_strmwidth($value, 0, 30, "...");
// }

public function getSummaryContentAttribute()
{
    return mb_strmwidth($this->content, 0, 30, "...");
}


```

O que fizemos agora foi: no lugar de modificar a string em todos lugares, criamos um novo atributo que usará o content, basta usar ele na view que você quer exibir ele.<br>

Chamaremos ele no `post.blade.php`.

```php
{{ post->summary_content }}
```
O Laravel auto compreende pelo nome usado no método.

### Mutators

##### Formatando string do comentário do POST

Queremos que no assunto do nosso comentário a primeira letra seja maiúscula.<br>
Dentro do model `Comment.php` iremos criar um método.

```php
public function setTitleAttribute($valor)
{
    $this->attribute['title'] = ucfirst($value);
}
```


##### Realizando cast de propriedade no model

Queremos alterar o campo `approved` do model Post, para que no lugar de retornar 0 ou 1, retorne true ou false.

No model `Post.php` criaremos uma variável:

```php
protected $casts = [
    'approved'=>'boolean'
]
```

Agora vamos testar no `tinker`

```php
App\Model\Post::find(2)->approved
```

Ele retorna boleano, <strong>funcionou!!!</strong>
Agora vamos testar um dado que está na lixeira.
```php
App\Model\Post::withTrashed()->find(1)->approved
```

<span style="font-size:30px;font-weight:bold;margin:10px">Serialização</span>

Serialização é o processo de conversão entre um formato e outro dentro da aplicação

### Escondendo campo da tabela
 Iremos esconder o campo do titulo do retorno dos comentários.
```php
protected $hidden = [ 
    "title"
];
```

Para testar vamos utilizar o `tinker`:

```php
App\Model\Comment::first()->toArray()
```

Vemos que o title realmente ficou invisivel. Porém ele ainda é acessível:

```php
App\Model\Comment::first()->title
```
E podemos recuperar todos os dados novamente, com os visiveis e invisiveis:
```php
App\Model\Comment::first()->makeVisible('title')->toArray()
```
Ele apareceu novamente.


### Deixando visivel apenas os campos que queremos

No Laravel temos uma palavra reservada para definirmos os elementos que queremos que fique visível, mas para usar ela não podemos usar mais o `$hidden`.<br>
Então nosso código fica assim:
```php
// protected $hidden = ["title"];
protected $visible = ['title','content']
```

Ao testarmos:

```php
App\Model\Comment::first()->toArray()
```
Ele retorna apenas o `title` e o `content`.<br>

Para trazer mais resultados, usamos os campos que queremos ver dentro do `makeVisible`:

```php
App\Model\Comment::first()->makeVisible('created_at')->toArray()
```


Também temos um método contrário ao `makeVisible` que consegue serializar em tempo de execução:
```php
App\Model\Comment::first()->makeHidden('title')->toArray()
```
Conseguimos tornar o campo `title` invisivel, ele que colocamos dentro do método `$visible`.

#### Acessando assessores na serialização

Ao acessarmos o model `Post` com o tinker a fim de recuperar os valores, conseguimos perceber que o assessor criado `summary_content` não é visível.<br>
Para podermos visualizá-lo usamos o método `append` e descrevemos quem queremos adicionar.

```php
App\Model\Comment::first()->append('summary_content')->toArray()
```
E funcionou!<br>
Mas percebemos que não seria uma boa prática repertirmos sempre esse método para trazê-lo, podemos fazer com que ele venha juntoao pesquisarmos os campos.<br>

No model `Post` vamos usar uma propriedade chamada `$appends`.


```php
protected $appends = [ 'summary_content' ];
```


Agora ele sempre estará disponível quando trazermos todos os dados.

<span style="font-size:30px;font-weight:bold;margin:10px">Recursos úteis</span>

### Como trabalhar com API Resource


Vamos criar um resource para Post, utilizaremos o `artisan` para isso.
```php
php artisan make:resource Post
```
Ele ficará localizado dentro de `App\Http\Resource`.<br>
Uma situação: <br>
* Queremos que o retorno dos campos seja em português, pois o cliente que usará é uma empresa nacional.<br>

```php
public function toArray($request)
{
    return [
        'codigo'    =>$this->id,
        'titulo'    =>$this->title,
        'conteudo'  =>$this->content,
        'aprovado'  =>$this->approved
    ];
}
```

Iremos criar o endpoint para testarmos os nosso recursos. Na pasta de `App\Routes\api.php` criaremos:
```php
Route::get('post/{id}', function($id){
    return new \App\Http\Resource\Post(\App\Post::find($id));
})
```

### Utilizando mais um banco dados no Laravel

Em `Config\database.php` encontraremos:

```php
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
```


Vamos criar uma nova conexão:

```php

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'outra' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB2_HOST', '127.0.0.1'),
            'port' => env('DB2_PORT', '3306'),
            'database' => env('DB2_DATABASE', 'forge'),
            'username' => env('DB2_USERNAME', 'forge'),
            'password' => env('DB2_PASSWORD', ''),
            'unix_socket' => env('DB2_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
```



E no nosso `.env` vamos configurar essa conexao:

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fortify
DB_USERNAME=root
DB_PASSWORD=

DB2_CONNECTION=mysql
DB2_HOST=127.0.0.1
DB2_PORT=3306
DB2_DATABASE=outra_conexao
DB2_USERNAME=root
DB2_PASSWORD=
```

No nosso model `User` como exemplo, vamos usar a conexao do nosso novo banco de dados configurado.

```php
protected $connection = 'outra';
```

Vamos testar com tinker:

```php
php artisan tinker

\App\Model\User::find(1)
```

E já trouxe os campos de usuários da tabela da nova conexão.


### Excessões no Eloquent


Podemos tratar algumas excessões comuns, por exemplo ao usar uma URL que o sistema não reconhece somos direcionados a uma página. Vamos tratar essas excessões para exibir uma mensagem no lugar de mostrar uma página 404.

```php
Route::get('post/{id}', function($id){

    try{
        return new \App\Http\Resource\Post(\App\Post::find($id));
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
        return response(['message'=>'Not Found'], 404)
    }

})
```
### Comparando models

No Eloquent conseguimos comparar duas instâncias de models

```php
php artisan tinker

$u1 = App\Model\User::find(1)
$u2 = App\Model\User::find(2)

$u1->is($u2)
// Retorna false

$u11 = App\Model\User::find(1)
$u1->is($u11)
// Retorna true
```


### Where dinâmico

No normalmente usamos a cláusula `where` assim:
```php
App\Model\User::where('name','Ana Leon')->get()

// Ele retorna um registro com o nome encontrado
```

Podemos usar `where` de forma dinâmica:

```php
App\Model\User::whereName('Ana Leon')->get()

// Ele retorna um registro com o nome encontrado
```
Podemos usar mais de um campo para verificar de forma dinâmica:

```php
App\Model\User::whereNameAndEmail('Ana Leon','ana@teste.com')->get()

// Ele retorna um registro com o nome e email encontrado
```

Podemos usar o `or` no where também


```php
App\Model\User::whereNameOrEmail('Ana Leon','outro@teste.com')->get()

// Ele retorna um registro com o nome encontrado e outro registro encontrado pelo email
```


