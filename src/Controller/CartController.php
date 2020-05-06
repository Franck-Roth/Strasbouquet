<?php


namespace App\Controller;

use App\Model\BouquetManager;
use App\Model\CartManager;
use App\Model\CartBouquetManager;
use DateTime;

class CartController extends AbstractController
{
    public function showBouquet()
    {
        $bouquetManager= new BouquetManager();
        $bouquets = $bouquetManager->selectAll();
        if (!isset($_SESSION['id_panier'])) {
            $panier="";
        } else {
            $panier = $_SESSION['id_panier'];
        }
        return $this->twig->render(
            'Front/boquuets.html.twig',
            ["bouquets" => $bouquets, "panier" => $panier]
        );
    }

    public function addBouquetCart($idBouquet)
    {
        if (!isset($_SESSION['user'])) {
            $message = "Vous devez vous inscrire ou vous connecter pour commmander";
            return $this->twig->render('User/add.html.twig', ["message" => $message]);
        } else {
            $cartManager = new CartManager();
            $cartBManager = new CartBouquetManager();
            $user = ($_SESSION['user']);
            $date = new DateTime("now");
            $date = $date->format("Y-m-d");

            $bouquetManager= new BouquetManager();
            $bouquets = $bouquetManager->selectAll();
            $panier = $_SESSION['id_panier'];


            if (!isset($_SESSION['id_panier'])) {
                $id = $cartManager->insert($user, $date);
                $_SESSION['id_panier'] =  $id;
            }
            if ($cartBManager->bouquetInCart($idBouquet) === false) {
                $cartBManager->addBouquetCart($idBouquet);
            } else {
                $qte = $cartBManager->selectQuantiteBouquet($idBouquet);
                $qte['quantite'] += 1;
                $qte = $qte['quantite'];
                $cartBManager->updateBouquetCart($idBouquet, $qte);
                return $this->twig->render(
                    'Front/bouquets.html.twig',
                    ["bouquets" => $bouquets, "panier" => $panier, "qte" => $qte]
                );
            }
            return $this->twig->render(
                'Front/bouquets.html.twig',
                ["bouquets" => $bouquets, "panier" => $panier]
            );
        }
    }

    public function showCart($id)
    {
        if (!isset($_SESSION['user'])) {
            $message = "Vous devez vous inscrire ou vous connecter pour commmander";
            return $this->twig->render('User/add.html.twig', ["message" => $message]);
        } else {
            $cartManager = new CartManager();
            $panier = $cartManager->showCartContent($id);
            $price = $cartManager->priceCart($id);
            return $this->twig->render('Front/cart.html.twig', ["panier" => $panier, "price" => $price]);
        }
    }
}
