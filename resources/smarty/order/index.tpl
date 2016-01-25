{include file="../template/header.tpl"}

{foreach from=$orders item=order}
    <p><a href="index.php?section=order&action=view&id={$order->id}">{$order->id}</p>
    <p>{$order->name}</p>
    <p>{$order->price}</a></p>
{/foreach}

{include file="../template/footer.tpl"}
