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
        $uri = '/api/users/login';
 
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
 
        $data = [
            'email' => $email,
            'password' => $password,
        ];
 
        return $this
            ->withHeaders($headers)
            ->postJson($uri, $data);
    }
 
    protected function logout(string $token)
    {
        $uri = '/api/users/logout';
 
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$token}",
        ];
 
        return $this
            ->withHeaders($headers)
            ->postJson($uri);
    }
 
    protected function myGetToken($response): string
    {
        return $response->json('data.token');
    }
 
    protected function myGet(string $uri, string $token)
    {
        return $this
            ->withHeaders($this->authHeaders($token))
            ->getJson($uri);
    }
 
    protected function myPost(string $uri, array $data, string $token)
    {
        return $this
            ->withHeaders($this->authHeaders($token))
            ->postJson($uri, $data);
    }
 
    protected function myPatch(string $uri, array $data, string $token)
    {
        return $this
            ->withHeaders($this->authHeaders($token))
            ->patchJson($uri, $data);
    }
 
    protected function myDelete(string $uri, string $token)
    {
        return $this
            ->withHeaders($this->authHeaders($token))
            ->deleteJson($uri);
    }
 
    protected function authHeaders(string $token): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }
}