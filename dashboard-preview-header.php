<?php
// This header is loaded only by dashboard-preview.php through header.php.
$previewHeaderEscape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$previewHeaderLoggedIn = !empty($_SESSION['username']);
$previewHeaderAdmin = (string) ($_SESSION['admin'] ?? '');
$previewHeaderWeek = (int) $curWeek > 0 ? (int) $curWeek : -4;
$previewHeaderSuffix = (int) $curWeek === 1 || (int) $curWeek === 10 ? '_A' : '';
$previewHeaderInfo = [
    ['Standings', 'standings.php'],
    ['Schedule', 'schedule.php?week=' . $previewHeaderWeek],
    ['Transaction Log', 'translog.php?team=menu&type=all'],
    ['Injury Log', 'injuries.php'],
    ['Team Finances', 'finances.php'],
];
if (!$previewHeaderLoggedIn) {
    $previewHeaderInfo[] = ['Dispersal Draft', '1985draft.php'];
}
$previewHeaderInfo[] = ['Sim Export', 'stats.php?page=Index'];
$previewHeaderInfo[] = ['Sim Export Mobile', 'http://thakfu.com/ptf/export/Index.html'];

$previewHeaderStats = [
    ['Team Stats', 'teamstats.php'],
    ['League Leaders', 'league_leaders.php'],
    ['Player Career Stats', 'career_stats.php'],
    ['League Records', 'league_records.php'],
];
$previewHeaderHistory = [
    ['Draft History', 'pastdraft.php?year=' . $year],
    ['Retired Players', 'retired.php'],
];
$previewHeaderGroups = ['Info' => $previewHeaderInfo];
if ($previewHeaderLoggedIn) {
    $previewHeaderGroups['Strategy'] = [
        ['Status', 'status.php'],
        ['Depth Chart', 'depth.php'],
        ['Coaching Profile', 'edit_coach.php'],
        ['Game Planning', 'gameplan.php'],
    ];
    $previewHeaderOffseason = [
        ['Upcoming Free Agents', 'upcoming-fa.php?sort=Overall&order=desc&pos=all'],
        ['Free Agency', 'freeagency.php?abbrev=' . rawurlencode((string) ($_SESSION['abbreviation'] ?? ''))],
    ];
    if ((int) $draftactive === 1) {
        $previewHeaderOffseason[] = [$year . ' Draft Pool', 'draftall.php?pos=all'];
        $previewHeaderOffseason[] = [$year . ' Draft', 'pastdraft.php?year=' . $year];
    } else {
        $previewHeaderOffseason[] = [((int) $year + 1) . ' Draft Preview', 'draftpreview.php?pos=all'];
    }
    $previewHeaderOffseason[] = ['1990 Combine Stats', 'http://www.thakfu.com/ptf/combine90/Index.html'];
    $previewHeaderGroups['Offseason'] = $previewHeaderOffseason;
    $previewHeaderGroups['Rosters'] = [
        ['All Players', 'allplayers.php'],
        ['Roster', 'rosters.php?team=' . rawurlencode((string) ($_SESSION['TeamID'] ?? '')) . '&table=y&sort=Jersey&order=asc'],
        ['Transactions', 'transactions.php'],
        ['Trades', 'trades.php'],
        ['Free Agents', 'freeagents.php'],
    ];
} else {
    $previewHeaderGroups['Transactions'] = [
        ['All Players', 'allplayers.php?sort=Overall&order=desc&pos=all'],
        ['Upcoming Free Agents', 'upcoming-fa.php?sort=Overall&order=desc&pos=all'],
    ];
}
$previewHeaderGroups['Stats'] = $previewHeaderStats;
$previewHeaderGroups['History'] = $previewHeaderHistory;
if ($previewHeaderLoggedIn && $previewHeaderAdmin === '2') {
    $previewHeaderGroups['Admin'] = [['Transactions', 'transactions_checker.php?team=0']];
}
$previewHeaderTeams = [
    [2, 'MIA'], [5, 'BUF'], [9, 'LON'], [18, 'BAL'], [1, 'NYT'],
    [20, 'LOU'], [4, 'OAK'], [7, 'CIN'], [8, 'SEA'], [17, 'CHC'],
    [6, 'NYG'], [10, 'IND'], [14, 'ATL'], [16, 'TB'], [19, 'WAS'],
    [3, 'GB'], [11, 'CHI'], [12, 'DET'], [13, 'MIN'], [15, 'SF'],
];
?>
<header class="ptf-preview-header">
    <div class="ptf-preview-top">
        <div class="ptf-preview-wrap ptf-preview-top-inner">
            <a class="ptf-preview-brand" href="dashboard-preview.php" aria-label="PTF League Central home">
                <img src="images/ptf_logo.png" alt="" width="58" height="58">
                <span class="ptf-preview-brand-copy"><strong>PTF</strong><small>LEAGUE CENTRAL</small></span>
            </a>
            <div class="ptf-preview-account">
                <span class="ptf-preview-online"><span class="ptf-preview-online-dot" aria-hidden="true"></span><a href="members.php"><?= $previewHeaderEscape($count['totalno'] ?? 0) ?> online</a></span>
                <?php if ($previewHeaderLoggedIn): ?>
                    <?php if ($previewHeaderAdmin === '2'): ?><a class="ptf-preview-admin" href="admin/index.php">Admin</a><?php endif; ?>
                    <?php if ($previewHeaderAdmin === '1'): ?><a class="ptf-preview-admin" href="admin/draftadmin.php">Draft Admin</a><?php endif; ?>
                    <a class="ptf-preview-profile" href="profile.php"><?= $previewHeaderEscape($_SESSION['username']) ?></a>
                    <?php $previewHeaderOfferCount = (int) ($offercount ?? 0); ?>
                    <?php if ($previewHeaderOfferCount > 0): ?><a class="ptf-preview-offers" href="profile.php"><?= $previewHeaderOfferCount ?> trade offer<?= $previewHeaderOfferCount === 1 ? '' : 's' ?></a><?php endif; ?>
                <?php else: ?>
                    <a class="ptf-preview-login" href="login.php">Log in <span aria-hidden="true">↗</span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <nav class="ptf-preview-navigation" aria-label="Main navigation">
        <div class="ptf-preview-wrap ptf-preview-nav-inner">
            <a class="ptf-preview-nav-home" href="dashboard-preview.php" aria-current="page">Dashboard</a>
            <a href="index.php">Classic Home</a>
            <?php foreach ($previewHeaderGroups as $previewHeaderLabel => $previewHeaderLinks): ?>
                <details class="ptf-preview-nav-group">
                    <summary><?= $previewHeaderEscape($previewHeaderLabel) ?></summary>
                    <div class="ptf-preview-nav-panel">
                        <?php foreach ($previewHeaderLinks as [$previewHeaderText, $previewHeaderHref]): ?>
                            <a href="<?= $previewHeaderEscape($previewHeaderHref) ?>"><?= $previewHeaderEscape($previewHeaderText) ?></a>
                        <?php endforeach; ?>
                    </div>
                </details>
            <?php endforeach; ?>
            <?php if ($previewHeaderLoggedIn): ?><a href="awards.php">Awards Voting</a><?php endif; ?>
        </div>
    </nav>
    <div class="ptf-preview-teams" role="navigation" aria-label="Teams">
        <div class="ptf-preview-wrap ptf-preview-teams-inner">
            <span class="ptf-preview-teams-label">TEAMS <span aria-hidden="true">/</span></span>
            <div class="ptf-preview-team-scroll">
                <?php foreach ($previewHeaderTeams as [$previewHeaderTeamId, $previewHeaderAbbrev]): ?>
                    <a href="team.php?team=<?= $previewHeaderTeamId ?>" aria-label="<?= $previewHeaderEscape($previewHeaderAbbrev) ?> team" title="<?= $previewHeaderEscape($previewHeaderAbbrev) ?>">
                        <img src="images/<?= $previewHeaderEscape($previewHeaderAbbrev . '_115' . $previewHeaderSuffix) ?>.png" alt="" width="38" height="38" loading="lazy">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</header>

