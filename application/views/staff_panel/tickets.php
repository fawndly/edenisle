<?php 
    $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'staff_panel'));
?>
<div class="form-filter" style="margin-top: 12px;">
    <form class="form-search" action="/staff_panel/tickets">
        <input type="text" class="input-medium" name="user" value="<?php echo $this->input->get('user') ?>" placeholder="Search by username..." />
        <select name="status" class="input-medium">
            <option value="">All Status</option>
            <option value="pending" <?php if ($this->input->get('status') == 'pending') echo 'selected'?>>Pending</option>
            <option value="solved" <?php if ($this->input->get('status') == 'solved') echo 'selected'?>>Solved</option>
        </select>
        <button type="submit" class="btn">Filter</button>
    </form>
</div>
<span class="paginate">
    <?php echo $this->pagination->create_links(); ?>
</span>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Ticket</th>
            <th>Created by</th>
            <th>Created on</th>
            <th>Status</th>
            <th>Solved by</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr style="opacity:<?= $ticket['status'] == 'pending' ? '1' : '0.7' ?>">
                <td><a href="/staff_panel/view_ticket/<?= $ticket['ticket_id'] ?>"><strong><?= $ticket['issue'] ?></strong></a></td>
                <td width="130"><?= $ticket['username'] ?></td>
                <td width="200"><small><?= date('D, M jS, Y (g:i:s A)', strtotime($ticket['timestamp'])) ?></small></td>
                <td width="80"><?= ucfirst($ticket['status']) ?></td>
                <td width="80"><?= ($ticket['attended_by']) ? ucfirst($ticket['attended_by']) : '-' ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<div class="clearfix"></div>
<span class="paginate">
    <?php echo $this->pagination->create_links(); ?>
</span>
