<?php
namespace Project\Controller;
use Project\Repository\OrderRepository;

class OrderController extends Controller
{
    public function __construct(\PDO $pdo, \Smarty $smarty)
    {
        parent::__construct($smarty, $pdo);
    }

    public function index()
    {
        $orderRepository = new OrderRepository($this->pdo);
        $orders = $orderRepository->getOrders();

        $this->smarty->assign('header', 'Orders');
        $this->smarty->assign('orders', $orders);
        $this->smarty->display('order/index.tpl');
    }

    public function view()
    {
        $id = $_GET['id'];

        $orderRepository = new OrderRepository($this->pdo);
        $order = $orderRepository->getOrder($id);

        $this->smarty->assign('header', 'Order');
        $this->smarty->assign('order', $order);
        $this->smarty->display('order/view.tpl');
    }

    public function add()
    {
        $this->smarty->assign('header', 'Order aanmaken!');
        $this->smarty->assign('name', '');
        $this->smarty->assign('user', '');

        $orderRepository = new OrderRepository($this->pdo);
//        $orders = $orderRepository->getOrders();
//        $this->smarty->assign('orders', $orders);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = $_POST['order_id'];

            $messages = [];

            if (empty($order_id)) {
                $messages = array_merge($messages, ['voorbeeld' => 'array']);

                array_push($messages, 'Je hebt geen User geselecteerd, opschiete!');

            }
            if ($_POST['price'] < 1) {
                array_push($messages, 'Gratis doen we niet aan!');
            }

            if (!empty($messages)) {
                $this->smarty->assign('messages', $messages);
                $this->smarty->display('customer/add.tpl');
            }


            $user = $orderRepository->getOrder($order_id);

//            $orderRepository = new OrderRepository($this->pdo);
            $order = $orderRepository->addOrder($_POST['name'], $_POST['price'], $_POST['user_id'],);
            header('location: index.php?section=customer&action=index');

        } else {
            $this->smarty->display('user/add.tpl');
        }
    }
}