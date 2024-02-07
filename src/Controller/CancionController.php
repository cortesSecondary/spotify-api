<?php

namespace App\Controller;

use App\Entity\AnyadeCancionPlaylist;
use App\Entity\Cancion;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CancionController extends AbstractController
{
    public function canciones(SerializerInterface $serializer)
    {
        //? GET path: /canciones
        $canciones = $this->getDoctrine()
            ->getRepository(Cancion::class)
            ->findAll();
        
        $canciones = $serializer->serialize(
            $canciones,
            'json',
            ['groups' => ['cancion', 'album']]
        );
        return new Response($canciones);
    }
    
    public function cancion(Request $request, SerializerInterface $serializer)
    {
        //? GET path: /cancion/{id}
        $id = $request->get("id");

        $cancion = $this->getDoctrine()
            ->getRepository(Cancion::class)
            ->findOneBy(['id' => $id]);

        $cancion = $serializer->serialize(
            $cancion,
            'json',
            ['groups' => ['cancion', 'album']]
        );
        return new Response($cancion);
    }

    public function canciones_playlist(Request $request, SerializerInterface $serializer)
    {
        //? GET path: /playlist/{id}/canciones
        $id = $request->get('id');

        $playlist = $this->getDoctrine()
            ->getRepository(Playlist::class)
            ->findOneBy(['id' => $id]);

        $tituloPlaylist = $playlist->getTitulo();

        $anyadeCancion = $this->getDoctrine()
            ->getRepository(AnyadeCancionPlaylist::class)
            ->findBy(['playlist' => $id]);

        $data = [
            'Titulo Playlist' => $tituloPlaylist,
            'Canciones' => $anyadeCancion,
        ];

        $data = $serializer->serialize(
            $data,
            'json',
            ['groups' => ['anyade_cancion_playlist', 'cancion']]);

        return new Response($data);
    }

    public function cancion_playlist(Request $request, SerializerInterface $serializer)
    {
        //TODO: POST path: /playlist/{id_playlist}/cancion/{id_cancion}

        $id_playlist = $request->get('id_playlist');
        $id_cancion = $request->get('id_cancion');

        if ($request->isMethod('POST'))
        {

            // $bodyData = $request->getContent();

            $playlist = $this->getDoctrine()
                ->getRepository(Playlist::class)
                ->findOneBy(['id' => $id_playlist]);
            
            $cancion = $this->getDoctrine()
                ->getRepository(Cancion::class)
                ->findOneBy(['id' => $id_cancion]);

            $id_usuario = $playlist->getUsuario();

            $usuario = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['id' => $id_usuario]);

            $anyadeCancion = new AnyadeCancionPlaylist();
            
            $anyadeCancion->setUsuario($usuario);
            $anyadeCancion->setPlaylist($playlist);
            $anyadeCancion->setCancion($cancion);
            
            $this->getDoctrine()->getManager()->persist($anyadeCancion);
            $this->getDoctrine()->getManager()->flush();

            $anyadeCancion = $serializer->serialize(
                $anyadeCancion,
                'json',
                ['groups' => ['anyade_cancion_playlist', 'usuario', 'playlist', 'cancion']]
            );
            return new Response($anyadeCancion);
        };
        return new JsonResponse(['msg' => $request->getMethod() . ' not allowed']);

        //! DELELE
        if ($request->isMethod('DELETE'))
        {

        }
    }
}
