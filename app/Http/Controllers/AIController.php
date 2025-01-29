<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function questions(Request $request)
    {

        $request->validate([
            'service_name' => 'required',
            'question_type' => 'required',
        ]);
        $prompt = <<<EOD
        Devuelve únicamente un JSON valido con un listado de preguntas relacionadas con el servicio que te proporcionaré. Las preguntas deben estar orientadas a filtrar compañías en función de las respuestas de los usuarios, similar al sistema de HomeAdvisor. Ten en cuenta que las compañías están ubicadas en Estados Unidos, por lo que debes utilizar las unidades de medida estándar de este país (como pulgadas, pies, libras, etc.). Si no tienes resultados adecuados, devuelve un array vacío. Si tienes resultados, las preguntas deben estar categorizadas en tres tipos:

1. **Comparables**: Preguntas que permiten comparar un valor con un umbral o rango. Las respuestas deben incluir un operador (como `==`, `>=`, `<=`, `>`, `<`, `><`) y un valor. Si el operador es `><` (BETWEEN), las respuestas deben tener dos valores, `value` y `value2`, que representen los límites inferior y superior del rango.
2. **Cerradas**: Preguntas con opciones predefinidas de respuesta que los usuarios pueden seleccionar.
3. **Abiertas**: Preguntas sin respuestas predefinidas. Los usuarios deben proporcionar una respuesta libre.
4. **Siempre devuelve 5 preguntas.
4. **Las preguntas y respuestas deben estar en ingles USA de la costa ESTE como preferencia.
Las preguntas deben estar orientadas a filtrar compañías según los siguientes criterios:
- **Cantidad**: Pregunta sobre el área a cubrir o el tamaño del servicio.
- **Materiales**: Pregunta sobre el tipo de material preferido.
- **Tiempo**: Pregunta sobre el tiempo en que se necesita el servicio.
- **Presupuesto**: Pregunta sobre el presupuesto disponible.
- **Cantidad de pisos**: Pregunta sobre la cantidad de pisos del edificio a intervenir en caso necesario.
- **Sistema metrico**: Utiliza siempre el sistema metrico imperial.

### Ejemplo de preguntas:
La respuesta debe ser un JSON válido, como este ejemplo no utilices la palabra json antes de la respuesta:
```
{
  "questions": [
    {
      "type": "comparables",
      "question": "¿Cuántos metros cuadrados necesita que cubra el techo?",
      "answers": [
        {"operator": "<", "value": 100, "label": "Menos de 100 metros cuadrados"},
        {"operator": "><", "value": 100, "value2": 300, "label": "Entre 100 y 300 metros cuadrados"},
        {"operator": ">", "value": 300, "label": "Más de 300 metros cuadrados"}
      ],
      "unit": "metros cuadrados"
    },
    {
      "type": "cerradas",
      "question": "¿Qué tipo de material desea para su techo?",
      "answers": [
        {"answer": "Tejas asfálticas", "label": "Tejas asfálticas"},
        {"answer": "Tejas de barro", "label": "Tejas de barro"},
        {"answer": "Metal", "label": "Metal"},
        {"answer": "Tejas de madera", "label": "Tejas de madera"}
      ]
    },
    {
      "type": "comparables",
      "question": "¿Cuántos pisos tiene el edificio que necesita el servicio?",
      "answers": [
        {"operator": "<", "value": 1, "label": "1 piso o menos"},
        {"operator": "><", "value": 1, "value2": 2, "label": "Entre 1 y 2 pisos"},
        {"operator": ">", "value": 2, "label": "Más de 2 pisos"}
      ]
    },
    {
      "type": "abiertas",
      "question": "¿Cuándo necesita que se realice el servicio de techado?"
    },
    {
      "type": "comparables",
      "question": "¿Cuál es el presupuesto estimado para el servicio?",
      "answers": [
        {"operator": "<", "value": 5000, "label": "Menos de $5,000"},
        {"operator": "><", "value": 5000, "value2": 10000, "label": "Entre $5,000 y $10,000"},
        {"operator": ">", "value": 10000, "label": "Más de $10,000"}
      ]
    }
  ]
}
EOD;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->timeout(1000)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $prompt,
                ],
                [
                    'role' => 'user',
                    'content' => 'Generate a list of questions related to the following service:' . $request->service_name.' and type of question:'. $request->question_type,
                ],
            ],
        ]);

        $questions = $response->json(); // Obtiene el JSON con las preguntas

        return $questions;
        if(isset($questions['choices'])){
            foreach ($questions['choices'] as $choice) {
                // Acceder a la propiedad content dentro de cada opción
                if (isset($choice['message']['content'])) {
                    $contents[] = json_decode($choice['message']['content']);
                }
            }
        }


        // Convertir el array $contents a JSO


        return $contents[0];
    }
}
