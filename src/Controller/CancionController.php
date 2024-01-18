<?php

namespace App\Controller;

use App\Entity\Cancion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CancionController extends AbstractController
{
    public function canciones(Request $request, SerializerInterface $serializer)
    {
        // path: /canciones
        $canciones = $this->getDoctrine()
            ->getRepository(Cancion::class)
            ->findAll();
        
        $canciones = $serializer->serialize(
            $canciones,
            'json',
            ['groups' => ['cancion']]
        );
        return new Response($canciones);
    }
    
    public function cancion(Request $request, SerializerInterface $serializer)
    {
        // path: /cancion/{id}
        $id = $request->get("id");

    }
    public function canciones_playlist(Request $request, SerializerInterface $serializer)
    {
        // path: /playlist/{id}/canciones

    }

}