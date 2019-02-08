<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?= lang('grants') ?></h2>
    </div>

    <?php if ( validation_errors() ): ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <?= validation_errors() ?>
        </div>
    <?php endif; ?>

    <div class="body">
        <form method="post">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel-group" id="accordion_grants">
                        <div id="cabecera" class="bg-white">
                            <table class="col-black" style="width: 100%;">
                                <tr>
                                    <th><label style="padding-left: 25px; background-color: white;"><?= lang('module') ?></label></th>
                                    <?php foreach ($actions as $action): ?>
                                        <th>
                                            <label class="m-b-0" style="padding-left: 35px;"><?= lang('method_'.$action) ?></label>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                        </div>

                        <?php foreach ($roles as $role_key => $role): ?>
                            <div class="panel panel-col-pink">
                                <div class="panel-heading" id="heading_rol_<?= $role->id ?>">
                                    <h4 class="panel-title">
                                        <a class="<?= ($role_key !== 0) ? 'collapsed' : '' ?>" data-toggle="collapse" href="#collapse_rol_<?= $role->id ?>">
                                            <?= $role->name ?> <small class="col-white right hidden-xs"><?= $role->description ?></small>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse_rol_<?= $role->id ?>" class="panel-collapse collapse <?= ($role_key === 0) ? 'in' : '' ?>">
                                    <div class="panel-body">
                                        <h5><?= $role->description ?></h5>

                                        <div class="">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <?php foreach ($actions as $action): ?>
                                                        <th>
                                                            <input type="checkbox" data-action="<?= $action ?>" id="check-all-<?= $role->id ?>-action-<?= $action ?>" class="filled-in chk-col-cyan check-all">
                                                            <label class="m-b-0" for="check-all-<?= $role->id ?>-action-<?= $action ?>"><?php echo lang($action); ?></label>
                                                        </th>
                                                    <?php endforeach; ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($modules as $module): ?>
                                                    <tr>
                                                        <th scope="row"><span data-toggle="tooltip" title="<?= $module->description ?>"><?= lang($module->lang); ?></span></th>
                                                        <?php foreach ($actions as $action): ?>
                                                            <td>
                                                                <?php if ( isset($grants[$role->id][$module->id][$action]) ): ?>
                                                                    <input type="hidden" value="delete" name="grants[<?= $role->id ?>][<?= $module->id ?>][<?= $action ?>]">
                                                                <?php endif; ?>
                                                                <input type="checkbox" value="<?= (isset($grants[$role->id][$module->id][$action])) ? 'ignore' : 'add' ?>" name="grants[<?= $role->id ?>][<?= $module->id ?>][<?= $action ?>]" data-action="<?= $action ?>" id="check-<?= $role->id ?>-<?= $module->id ?>-<?= $action ?>" class="filled-in chk-col-cyan check-action" <?= (isset($post_grants[$role->id][$module->id][$action]) && $post_grants[$role->id][$module->id][$action] !== 'delete') ? 'checked' : '' ?> <?= ($module->{'action_'.$action} != true) ? 'disabled' : '' ?>>
                                                                <label class="m-b-0" for="check-<?= $role->id ?>-<?= $module->id ?>-<?= $action ?>"></label>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 align-center">
                    <button type="submit" class="btn btn-info"><?= lang('submit') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>