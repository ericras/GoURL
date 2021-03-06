<?php $page->doctitle = 'Your URLs - Go URL | University of Nebraska&ndash;Lincoln'; ?>
<?php ob_start() ?>
<ul>
    <li><a href="http://www.unl.edu">UNL</a></li>
    <li><a href="<?php echo $lilurl->getBaseUrl() ?>">Go URL</a></li>
    <li>Your URLs</li>
</ul>
<?php $page->breadcrumbs = ob_get_clean(); ?>
<h1>Your Go URLs</h1>
<div class="wdn-band">
    <div class="wdn-inner-wrapper">
        <?php $urls = $lilurl->getUserURLs(phpCAS::getUser()); ?>
        <?php if ($urls->columnCount()): ?>
            <table class="go-urls wdn_responsive_table flush-left" data-order="[[ 2, &quot;desc&quot; ]]">
                <thead>
                    <tr>
                        <th>Short URL</th>
                        <th>Long URL</th>
                        <th>Created on</th>
                        <th data-searchable="false" data-orderable="false">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $urls->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php
                    $rowDateTime = null;
                    if ($row['submitDate'] !== '0000-00-00 00:00:00') {
                        $rowDateTime = new DateTime($row['submitDate']);
                    }
                    ?>
                    <tr>
                        <td data-header="Short URL"><a href="<?php echo $lilurl->getBaseUrl($row['urlID']); ?>"><?php echo $row['urlID']; ?></a></td>
                        <td data-header="Long URL"><a href="<?php echo $escape($row['longURL']) ?>"><?php echo $escape($row['longURL']) ?></a></td>
                        <td data-header="Created on"<?php if ($rowDateTime): ?> data-search="<?php echo $rowDateTime->format('M j, Y m/d/Y') ?>" data-order="<?php echo $rowDateTime->format('U') ?>"<?php endif; ?>>
                            <?php if ($rowDateTime): ?>
                                <?php echo $rowDateTime->format('M j, Y') ?>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a class="wdn-button go-url-qr" href="<?php echo $lilurl->getBaseUrl($row['urlID'] . '.qr') ?>" title="QR Code for <?php echo $row['urlID']; ?> Go URL"><span class="qrImage"></span> QR Code®</a>
                            <form action="<?php echo $lilurl->getBaseUrl('a/links') ?>" method="post">
                                <input type="hidden" name="urlID" value="<?php echo $row['urlID']; ?>" />
                                <button type="submit" onclick="return confirm('Are you for sure?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You haven't created any Go URLs, yet.</p>
        <?php endif;?>
    </div>
</div>

<script>
require(['jquery', 'wdn', 'https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js'], function($, WDN) {
    WDN.initializePlugin('modal', [function() {
        $('.go-url-qr').colorbox({photo:true, maxWidth: "75%"});
    }]);

    $(function() {
        $('.go-urls').DataTable();
    });
});
</script>
