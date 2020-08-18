<?php

namespace App\Controller;

use App\Entity\Key;
use App\Entity\Waypoint;
use App\Repository\KeyRepository;
use App\Repository\UserRepository;
use App\Repository\WaypointRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use UnexpectedValueException;

/**
 * @Route("/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/", name="map")
     */
    public function index(): Response
    {
        return $this->render('map/index.html.twig');
    }

    /**
     * @Route("/waypoints", name="map-json")
     * IsGranted("ROLE_AGENT")
     */
    public function mapJson(
        WaypointRepository $waypointRepository,
        Request $request
    ): JsonResponse {
        // $mapGroup = $mapGroupRepository->findOneBy(
        //     ['name' => $request->get('group', '4E')]
        // );
        //
        // if (!$mapGroup) {
        //     throw new UnexpectedValueException('Map group not found!');
        // }

        $wayPoints = $waypointRepository->findAll();

        $array = [];

        foreach ($wayPoints as $wayPoint) {
            $a = [];

            $a['id'] = $wayPoint->getId();
            $a['name'] = $wayPoint->getName();
            $a['lat'] = $wayPoint->getLat();
            $a['lng'] = $wayPoint->getLon();

            $array[] = $a;
        }

        return $this->json($array);
    }


    /**
     * @Route("/waypoint-info/{id}")
     * IsGranted("ROLE_AGENT")
     */
    public function mapAgentInfo(
        Security $security,
        Waypoint $waypoint
        // Agent $agent,
        // TranslatorInterface $translator,
        // Packages $assetsManager,
        // UserRepository $userRepository
    ): Response {
        $response = [];

        // if ($this->isGranted('ROLE_AGENT')) {
        //     $statsLink = $imgPath = '';
        //     switch ($agent->getFaction()->getName()) {
        //         case 'ENL':
        //             $statsLink = $this->generateUrl(
        //                 'agent_stats',
        //                 ['id' => $agent->getId()]
        //             );
        //             $imgPath = $assetsManager->getUrl(
        //                 'build/images/logos/ENL.svg'
        //             );
        //             break;
        //         case 'RES':
        //             $imgPath = $assetsManager->getUrl(
        //                 'build/images/logos/RES.svg'
        //             );
        //             break;
        //         default:
        //             throw new UnexpectedValueException('Unknown faction');
        //     }
        //     $user = $userRepository->findByAgent($agent);
        //     $userPic = $user && $user->getAvatarEncoded()
        //         ? sprintf(
        //             '<img src="%s" alt="Avatar" style="height: 32px;">',
        //             $user->getAvatarEncoded()
        //         )
        //         : '';
        //     $link = $this->generateUrl('agent_show', ['id' => $agent->getId()]);
        //     $response[] = sprintf(
        //         '<a href="%s"><img src="%s" alt="logo" style="height: 32px;">%s %s</a>',
        //         $link,
        //         $imgPath,
        //         $userPic,
        //         $agent->getNickname()
        //     );
        //     if ($agent->getRealName()) {
        //         $response[] = $agent->getRealName();
        //     }
        //     if ($statsLink) {
        //         $response[] = sprintf('<a href="%s">Stats</a>', $statsLink);
        //     }
        // } else {
        //     $response[] = $translator->trans('Please log in');
        // }
        // if ($this->isGranted('ROLE_ADMIN')) {
        //     $response[] = '';
        //     $response[] = 'More ADMIN info... TBD';
        // }

        $user = $security->getUser();
        if (null === $user) {
            throw new UnexpectedValueException('No user :(');
        }

        $response[] = sprintf('<b>%s</b>', $waypoint->getName());

        $userKeyCount = 0;
        $keys = [];
        if (count($waypoint->getWaypointKeys())) {
            $response[] = 'Keys:';
            foreach ($waypoint->getWaypointKeys() as $key) {
                if ($key->getUser() === $user) {
                    // $response[] = sprintf('MY %s: %d',$key->getUser(),$key->getCount());
                    $userKeyCount = $key->getCount();
                } else {
                    $keys[$key->getUser()->getUsername()] = $key->getCount();
                    $response[] = sprintf('%s: %d',$key->getUser(),$key->getCount());
                }
            }

        } else {
            $response[] = 'No keys available :(:';
        }

        $response[] = $user->getUsername().': <input value="'.$userKeyCount.'">'
        .'<button onClick="updateWaypointKeyCount('.$waypoint->getId().','.$user->getId().', this.previousSibling.value)">'
            .'Update'
            .'</button>'
        ;
        return $this->render('map/_wp_info.html.twig',[
            'waypoint' => $waypoint,
            'keys' => $keys,
            'user' => $user,
            'userKeyCount' => $userKeyCount,
        ]);


        return new Response(implode('<br>', $response));
    }
    /**
     * @Route("/update-waypoint-key-count/{id}", name="update-waypoint-key-count")
     * IsGranted("ROLE_AGENT")
     */
    public function updateKeyCount(
        Waypoint $waypoint,
        KeyRepository $keyRepository,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse
    {
        $userId = $request->request->get('userId');
        $count = $request->request->get('count');

        $user = $userRepository->findOneBy(['id' => $userId]);

        if (!$user) {
            throw new UnexpectedValueException('Unknown user');
        }

        $key = $keyRepository->findOneBy(
            [
'waypoint'=>$waypoint,
'user'=>$user
            ]
        );

        if ($key) {
            $key->setCount($count);
        } else {
            $key = (new Key())
                ->setWaypoint($waypoint)
                ->setUser($user)
                ->setCount($count);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($key);
        $entityManager->flush();

        return $this->json(['OK'.$waypoint->getName().$userId.$count]);
    }
}
