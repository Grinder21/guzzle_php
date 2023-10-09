<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;
class JsonPlaceholderAPI
{
    private $apiUrl;
    private $httpClient;

    public function __construct()
    {
        $this->apiUrl = 'https://jsonplaceholder.typicode.com';
        $this->httpClient = new Client();
    }

    public function getUsers()
    {
        $response = $this->httpClient->get($this->apiUrl . '/users');
        $users = json_decode($response->getBody(), true);
        return $users;
    }

    public function getUserPosts($userId)
    {
        $response = $this->httpClient->get($this->apiUrl . '/posts', ['query' => ['userId' => $userId]]);
        $posts = json_decode($response->getBody(), true);
        return $posts;
    }

    public function getUserTodos($userId)
    {
        $response = $this->httpClient->get($this->apiUrl . '/todos', ['query' => ['userId' => $userId]]);
        $todos = json_decode($response->getBody(), true);
        return $todos;
    }

    public function addPost($userId, $title, $body)
    {
        $response = $this->httpClient->post(
            $this->apiUrl . '/posts',
            [
                'json' => [
                    'userId' => $userId,
                    'title' => $title,
                    'body' => $body
                ]
            ]
        );
        $post = json_decode($response->getBody(), true);
        return $post;
    }

    public function updatePost($postId, $userId, $title, $body)
    {
        $response = $this->httpClient->put(
            $this->apiUrl . '/posts/' . $postId,
            [
                'json' => [
                    'userId' => $userId,
                    'title' => $title,
                    'body' => $body
                ]
            ]
        );
        $post = json_decode($response->getBody(), true);
        return $post;
    }

    public function deletePost($postId)
    {
        $response = $this->httpClient->delete($this->apiUrl . '/posts/' . $postId);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }
}


$api = new JsonPlaceholderAPI();

// получаю всех пользователей
$users = $api->getUsers();
var_dump($users);

// получаю посты пользователя с ID 1
$userId = 1;
$posts = $api->getUserPosts($userId);
var_dump($posts);

// делаю получение заданий пользователя с ID 1
$todos = $api->getUserTodos($userId);
var_dump($todos);

// делаю добавление нового поста для пользователя с ID 1
$newPost = $api->addPost($userId, 'Новый заголовок поста', 'Содержимое поста');
var_dump($newPost);

// делаю обновление существующего поста (ID = 1) пользователя с ID 1
$updatedPost = $api->updatePost(1, $userId, 'Обновленный заголовок поста', 'Новое содержимое поста');
var_dump($updatedPost);

// реализация удаления поста (ID = 1)
$postIdToDelete = 1;
if ($api->deletePost($postIdToDelete)) {
    echo "Пост с ID - $postIdToDelete успешно удален.";
} else {
    echo "Не удалось удалить сообщение.";
}

?>