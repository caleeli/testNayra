<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Instance;
use App\Token;

class ExampleTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        //Inicia el proceso PROCESS_1 de bpmn/Lanes.bpmn
        $this->artisan('bpmn:callProcess', [
            'filename' => 'bpmn/Lanes.bpmn',
            'process' => 'PROCESS_1',
        ]);
        
        //Assertion: Verifica que se ha creado una instancia
        $this->assertEquals(Instance::count(), 1);
        //Assertion: Verifica que se ha creado un token
        $this->assertEquals(token::count(), 1);

        //Obtiene los registros de instancia y token
        $instance = Instance::first();
        $token = Token::first();

        //Se completa el token
        $this->artisan('bpmn:completeTask', [
            'filename' => 'bpmn/Lanes.bpmn',
            'process' => 'PROCESS_1',
            'instanceId' => $instance->id,
            'tokenId' => $token->id,
        ]);

        //Assertion: Verifica que ahora existen dos tokens
        $this->assertEquals(2, token::count());
        
        //Assertion: Verifica que el primer token esta CLOSED
        $tokens = Token::orderBy('id')->get();
        $this->assertEquals('CLOSED', $tokens[0]->status);

        //Assertion: Verifica que el segundo token esta ACTIVE
        $this->assertEquals('ACTIVE', $tokens[1]->status);
    }
}
