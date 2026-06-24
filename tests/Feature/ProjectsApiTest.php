<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class ProjectsApiTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    public function testGetProjectsEmpty()
    {
        $result = $this->get('api/projects');
        
        $result->assertStatus(200);
        $result->assertJSONExact([]);
    }

    public function testCreateProject()
    {
        $data = [
            'title' => 'Test Project',
            'slug' => 'test-project',
            'description' => 'This is a test project',
            'image_url' => 'http://example.com/image.jpg',
            'tech_stack' => 'PHP, HTML',
            'portfolio_url' => 'http://example.com'
        ];

        $result = $this->post('api/projects', $data);
        
        $result->assertStatus(201);
        $result->assertJSONFragment(['messages' => ['success' => 'Data project berhasil ditambahkan']]);

        $this->seeInDatabase('projects', ['title' => 'Test Project']);
    }

    public function testShowProject()
    {
        $db = db_connect();
        $db->table('projects')->insert([
            'title' => 'Project For Show',
            'slug' => 'project-show',
            'description' => 'Test show'
        ]);
        $id = $db->insertID();

        $result = $this->get('api/projects/' . $id);
        
        $result->assertStatus(200);
        $result->assertJSONFragment(['title' => 'Project For Show']);
    }

    public function testUpdateProject()
    {
        $db = db_connect();
        $db->table('projects')->insert([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'description' => 'Old description'
        ]);
        $id = $db->insertID();

        $data = [
            'title' => 'New Title',
            'description' => 'New Description'
        ];

        $result = $this->withBodyFormat('json')->put('api/projects/' . $id, $data);
        
        $result->assertStatus(200);
        $result->assertJSONFragment(['messages' => ['success' => 'Data project berhasil diupdate']]);

        $this->seeInDatabase('projects', ['title' => 'New Title', 'id' => $id]);
    }

    public function testDeleteProject()
    {
        $db = db_connect();
        $db->table('projects')->insert([
            'title' => 'To Be Deleted',
            'slug' => 'deleted',
            'description' => 'Test'
        ]);
        $id = $db->insertID();

        $result = $this->delete('api/projects/' . $id);
        
        $result->assertStatus(200);
        $result->assertJSONFragment(['messages' => ['success' => 'Data project berhasil dihapus']]);

        $this->dontSeeInDatabase('projects', ['id' => $id]);
    }
}
