<?php
namespace Project\Repository;
use Project\Model\Order;
use Project\Model\User;

class OrderRepository extends Repository
{

    public function getOrders() : array
    {
        $query = 'SELECT * FROM `order`';

        $statement = $this->db->prepare($query);
        $statement->execute();
        $orders = $statement->fetchAll(\PDO::FETCH_OBJ);

        return $orders;
    }

    public function addOrder(string $name, float $price, User $user) : Order
    {

        // define the first part of the query
        $query = 'INSERT INTO `order` (`order`.`name`, `order`.`price`, `order`.`user_id` ';

        // prepare the required statements
        $statements = [
            ':name' => $name,
            ':price' => $price,
            ':user_id' => $user->id
        ];

        // can optionally be added
        if (!is_null($name)) {
            $query .= ', `order`.`name`';
            $statements = array_merge($statements, [':name' => $name]);
        }

        if (is_null($price)) {
            $query .= ', `order`.`price`';
            $statements = array_merge($statements, [':price' => $price]);
        }

        if (is_null($user)) {
            $query .= ', `order`.`user_id`';
            $statements = array_merge($statements, [':user_id' => $user->id]);
        }

        // close the query, start the VALUES part
        $query .= ') VALUES (:name, :password, :price, :user_id ';

        // close the query
        $query .= ')';

        // create a statement (prepared)
        $statement = $this->db->prepare($query);
        $statement->execute($statements); // insert the prepared statements and run the query
        $id = $this->db->lastInsertId(); // fetch last id

        // create and return a new Order object
        $order = new Order();
        $order->id = $id;
        $order->name = $name;
        $order->price = $price;
        $order->user = $user;

        return $order;
    }

    public function getOrder($id) : Order
    {
        $query = 'SELECT * FROM `order` WHERE id = :id';

        $statement = $this->db->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $order = $statement->fetchObject(Order::class);
        return $order;
    }
}