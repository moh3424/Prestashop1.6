{if isset($nouveauproduits) && !empty($nouveauproduits)}
    <div id="nouveauproduits">
        <h1>Des produits proposés</h1>
        <div class="row">
            {foreach from=$nouveauproduits item=nouveauproduit}
                <div calss="col col-md-3">
                    <div class="nouveauproduits">
                        <img src="{$nouveauproduit_path}{$nouveauproduit.id_nouveauproduit}.jpg"  alt="">
                        <h3>{$nouveauproduit.name}</h3>
                        <h4>{$nouveauproduit.title}</h4>
                        <p>{$nouveauproduit.description}</p>
                    </div>
                </div>
            {/foreach}
        </div>
        <p class="clearfix"><a href="{$link->getModuleLink('nouveauproduit', 'nouveauproduits')}" class="btn btn-primary pull-right">Voir +++</a></p>
    </div>
{/if}