<?php
// Reuse the shared setup, then render a preview-only header without changing legacy pages.
ob_start();
include __DIR__ . '/header.php';
$previewLegacyOutput = ob_get_clean();
$previewHeadEnd = stripos($previewLegacyOutput, '</head>');
$previewBodyStart = stripos($previewLegacyOutput, '<body>', $previewHeadEnd === false ? 0 : $previewHeadEnd);
if ($previewHeadEnd === false || $previewBodyStart === false) {
    throw new RuntimeException('The shared header could not be rendered for the dashboard preview.');
}
echo substr($previewLegacyOutput, 0, $previewHeadEnd);
echo '<link rel="stylesheet" href="css/dashboard-preview.css">';
echo substr($previewLegacyOutput, $previewHeadEnd, $previewBodyStart - $previewHeadEnd);
echo '<body class="ptf-preview-page">';
include __DIR__ . '/dashboard-preview-header.php';
unset($previewLegacyOutput);

function previewEscape($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function previewText($value, string $fallback = ''): string
{
    $text = trim((string) ($value ?? ''));
    return $text !== '' ? $text : $fallback;
}

function previewTeam(array $transaction, string $side): string
{
    $city = previewText($transaction['City' . $side] ?? null);
    $mascot = previewText($transaction['Mascot' . $side] ?? null);
    return previewText(trim($city . ' ' . $mascot), 'A team');
}

function previewTransaction(array $transaction): array
{
    $type = previewText($transaction['type'] ?? null);
    $oldTeam = previewTeam($transaction, 'Old');
    $newTeam = previewTeam($transaction, 'New');
    $player = previewText(trim(
        previewText($transaction['Position'] ?? null) . ' ' .
        previewText($transaction['FirstName'] ?? null) . ' ' .
        previewText($transaction['LastName'] ?? null)
    ), 'a player');
    $isPick = str_starts_with((string) ($transaction['PlayerID'] ?? ''), 'p');

    switch ($type) {
        case 'cut': return ['Released', "$oldTeam released $player to free agency.", 'Old'];
        case 'squad': return ['Practice squad', "$oldTeam moved $player to the practice squad.", 'Old'];
        case 'ir': return ['Injured reserve', "$oldTeam placed $player on injured reserve.", 'Old'];
        case 'activate': return ['Activated', "$oldTeam activated $player from injured reserve.", 'Old'];
        case 'sign': return ['Signed', "$newTeam signed $player from the free agent pool.", 'New'];
        case 'extend': return ['Extension', "$newTeam extended $player's contract.", 'New'];
        case 'extdecline': return ['Extension declined', "$player declined an extension with $newTeam.", 'New'];
        case 'extbreak': return ['Talks ended', "$player declined an extension with $newTeam; talks ended.", 'New'];
        case 'tag': return ['Franchise tag', "$newTeam placed the franchise tag on $player.", 'New'];
        case 'promote': return ['Promoted', "$newTeam promoted $player from the practice squad.", 'New'];
        case 'change': return ['Position change', "$oldTeam changed $player's position.", 'Old'];
        case 'expand': return ['Expansion draft', "$newTeam selected $player in the expansion draft.", 'New'];
        case 'supp': return ['Supplemental draft', "$newTeam selected $player in the supplemental draft.", 'New'];
        case 'draft': return ['Draft', "$newTeam drafted $player.", 'New'];
        case 'fasign': return ['Free agency', "$newTeam signed $player in free agency.", 'New'];
        case 'trade':
            if ($isPick) {
                return ['Trade', "$newTeam traded a draft pick to $oldTeam.", 'New'];
            }
            return ['Trade', "$oldTeam traded $player to $newTeam.", 'Old'];
        default: return ['League move', 'A transaction was recorded in the league log.', 'New'];
    }
}

function previewDate($value): string
{
    $raw = previewText($value);
    if ($raw === '' || $raw === '0000-00-00' || $raw === '0000-00-00 00:00:00') {
        return '';
    }
    $timestamp = strtotime($raw);
    return $timestamp === false ? '' : date('M j, Y', $timestamp);
}

$transactions = transService('front', '', '');
$transactions = is_array($transactions) ? $transactions : [];
$season = previewText($year ?? null, 'Current');
$weekNumber = filter_var($curWeek ?? null, FILTER_VALIDATE_INT);
$phase = $weekNumber !== false && $weekNumber > 0
    ? (($isPlayoffs ?? false) ? 'Playoffs · Week ' : (($isPreseason ?? false) ? 'Preseason · Week ' : 'Week ')) . $weekNumber
    : 'Offseason';
$nextSimulation = previewDate($nextSim ?? null);
$lastSimulation = previewDate($lastSim ?? null);
$scheduleWeek = $weekNumber !== false && $weekNumber > 0 ? $weekNumber : -4;
?>
<main class="dashboard-preview" id="main-content">
    <div class="dp-shell">
        <div class="dp-kicker"><span class="dp-live-dot" aria-hidden="true"></span> PTF LEAGUE CENTRAL <span class="dp-kicker-rule" aria-hidden="true"></span> STAGING PREVIEW</div>

        <section class="dp-hero" aria-labelledby="dp-title">
            <div class="dp-hero-copy">
                <p class="dp-overline"><?= previewEscape($leagueabbrev ?? 'PTF') ?> / <?= previewEscape($season) ?> SEASON</p>
                <h1 id="dp-title"><?= previewEscape(previewText($leagueName ?? null, 'Professional Thakfu Football')) ?></h1>
                <p class="dp-lede">Your league, at a glance. Follow the season and the moves shaping it.</p>
                <div class="dp-hero-actions">
                    <a class="dp-button dp-button-primary" href="standings.php">View standings <span aria-hidden="true">↗</span></a>
                    <a class="dp-button dp-button-secondary" href="schedule.php?week=<?= previewEscape($scheduleWeek) ?>">League schedule <span aria-hidden="true">↗</span></a>
                </div>
            </div>
            <div class="dp-scoreboard" aria-label="League status">
                <div class="dp-scoreboard-top"><span>LEAGUE STATUS</span><span class="dp-on-air">SEASON TRACKER</span></div>
                <div class="dp-scoreboard-main"><strong><?= previewEscape($season) ?></strong><span>SEASON</span></div>
                <div class="dp-scoreboard-bottom"><span><?= previewEscape($phase) ?></span><span><?= previewEscape(previewText($teamNum ?? null, '—')) ?> TEAMS</span></div>
            </div>
        </section>

        <section class="dp-status-grid" aria-label="Season snapshot">
            <div class="dp-stat"><span class="dp-stat-label">CURRENT PHASE</span><strong><?= previewEscape($phase) ?></strong><span class="dp-stat-note">League calendar</span></div>
            <div class="dp-stat"><span class="dp-stat-label">NEXT SIMULATION</span><strong><?= previewEscape($nextSimulation !== '' ? $nextSimulation : 'To be announced') ?></strong><span class="dp-stat-note">As listed by the league</span></div>
            <div class="dp-stat"><span class="dp-stat-label">LAST SIMULATION</span><strong><?= previewEscape($lastSimulation !== '' ? $lastSimulation : 'Not listed') ?></strong><span class="dp-stat-note">Latest league date</span></div>
        </section>

        <div class="dp-content-grid">
            <section class="dp-panel dp-transactions" aria-labelledby="dp-transactions-title">
                <div class="dp-section-heading"><div><p class="dp-overline">AROUND THE LEAGUE</p><h2 id="dp-transactions-title">Latest transactions</h2></div><a href="translog.php?team=menu&amp;type=all">Full transaction log <span aria-hidden="true">↗</span></a></div>
                <?php if ($transactions === []): ?>
                    <p class="dp-empty">No recent transactions are available.</p>
                <?php else: ?>
                    <ol class="dp-feed">
                        <?php foreach ($transactions as $transaction):
                            if (!is_array($transaction)) { continue; }
                            [$label, $description, $side] = previewTransaction($transaction);
                            $abbreviation = previewText($transaction['Abbrev' . $side] ?? null, 'PTF');
                            $date = previewDate($transaction['date'] ?? null);
                        ?>
                        <li class="dp-feed-item">
                            <span class="dp-team-badge" aria-hidden="true"><?= previewEscape($abbreviation) ?></span>
                            <div class="dp-feed-copy"><span class="dp-feed-type"><?= previewEscape($label) ?></span><p><?= previewEscape($description) ?></p></div>
                            <?php if ($date !== ''): ?><time class="dp-feed-date" datetime="<?= previewEscape(date('Y-m-d', strtotime((string) $transaction['date']))) ?>"><?= previewEscape($date) ?></time><?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </section>

            <aside class="dp-rail" aria-label="Explore the league">
                <section class="dp-panel dp-explore"><p class="dp-overline">THE FIELD</p><h2>Explore the league</h2><p class="dp-rail-intro">The pages you already use, together in one place.</p>
                    <a href="schedule.php?week=<?= previewEscape($scheduleWeek) ?>"><span><b>01</b> Schedule &amp; results</span><span aria-hidden="true">↗</span></a>
                    <a href="standings.php"><span><b>02</b> Standings</span><span aria-hidden="true">↗</span></a>
                    <a href="league_leaders.php"><span><b>03</b> League leaders</span><span aria-hidden="true">↗</span></a>
                    <a href="teamstats.php"><span><b>04</b> Team stats</span><span aria-hidden="true">↗</span></a>
                </section>
                <section class="dp-panel dp-community"><p class="dp-overline">OFF THE FIELD</p><h2>League community</h2><p>Keep the conversation going between simulations.</p><a href="https://discord.gg/SYBkaejsae" target="_blank" rel="noopener noreferrer">Join the PTF Discord <span aria-hidden="true">↗</span></a></section>
            </aside>
        </div>
        <div class="dp-footer"><span>PTF / LEAGUE CENTRAL</span><span>Preview for staging review</span></div>
    </div>
</main>


