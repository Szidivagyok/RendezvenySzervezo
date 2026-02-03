<?php
 
namespace Tests;
 
use Illuminate\Foundation\Testing\DatabaseTransactions;
 
 class TestBase extends TestCase
{
    use DatabaseTransactions;
 
    protected function login(
        string $email = 'admin@example.com',
        string $password = '123'
    ) {
        return $this->postJson('/api/users/login', [
            'email' => $email,
            'password' => $password,
        ], [
            'Accept' => 'application/json',
        ]);
    }
 
    protected function logout(string $token)
    {
        return $this->postJson(
            '/api/users/logout',
            [],
            $this->authHeaders($token)
        );
    }
 
    protected function myGetToken($response): string
    {
        return $response->json('data.token');
    }
 
    protected function myGet(string $uri, string $token)
    {
        return $this->getJson($uri, $this->authHeaders($token));
    }
 
    protected function myPost(string $uri, array $data, string $token)
    {
        return $this->postJson($uri, $data, $this->authHeaders($token));
    }
 
    protected function myPatch(string $uri, array $data, string $token)
    {
        return $this->patchJson($uri, $data, $this->authHeaders($token));
    }
 
    protected function myDelete(string $uri, string $token)
    {
        return $this->deleteJson($uri, [], $this->authHeaders($token));
    }
 
    protected function authHeaders(string $token): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }
}