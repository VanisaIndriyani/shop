<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('wilayah:mirror {--province= : Mirror only specific province code, e.g. 34}', function () {
    $provinceOpt = (string) ($this->option('province') ?? '');
    $base = base_path('public/wilayah');
    if (!is_dir($base)) {
        @mkdir($base, 0777, true);
    }
    @mkdir($base . '/regencies', 0777, true);
    @mkdir($base . '/districts', 0777, true);

    $fetchJson = function (string $url): ?array {
        try {
            $ctx = stream_context_create(['http' => ['timeout' => 20]]);
            $raw = @file_get_contents($url, false, $ctx);
            if ($raw === false) return null;
            $j = json_decode($raw, true);
            return is_array($j) ? $j : null;
        } catch (\Throwable $e) {
            return null;
        }
    };
    $writeJson = function (string $path, array $data): void {
        $dir = dirname($path);
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    };

    $this->info('Mirroring provinces...');
    $provinces = $fetchJson('https://wilayah.id/api/provinces.json');
    if (!$provinces || !isset($provinces['data'])) {
        $this->error('Failed to fetch provinces');
        return 1;
    }
    $writeJson($base . '/provinces.json', $provinces);
    $provList = $provinces['data'];
    $count = 0;

    foreach ($provList as $p) {
        $code = (string) ($p['code'] ?? $p['id'] ?? '');
        $name = (string) ($p['name'] ?? '');
        if ($provinceOpt !== '' && $provinceOpt !== $code) {
            continue;
        }
        if ($code === '') continue;
        $this->info("Prov {$code} - {$name}: regencies...");
        $regs = $fetchJson("https://wilayah.id/api/regencies/{$code}.json");
        if ($regs && isset($regs['data'])) {
            $writeJson($base . "/regencies/{$code}.json", $regs);
            foreach ($regs['data'] as $r) {
                $rCode = (string) ($r['code'] ?? $r['id'] ?? '');
                $rName = (string) ($r['name'] ?? '');
                if ($rCode === '') continue;
                $this->line("  - districts {$rCode} - {$rName}");
                $dists = $fetchJson("https://wilayah.id/api/districts/{$rCode}.json");
                if ($dists && isset($dists['data'])) {
                    $writeJson($base . "/districts/{$rCode}.json", $dists);
                    $count += count($dists['data']);
                } else {
                    $this->warn("    failed districts: {$rCode}");
                }
            }
        } else {
            $this->warn("  failed regencies: {$code}");
        }
    }

    $this->info("Done. District files written: {$count}");
    return 0;
})->purpose('Mirror dataset wilayah Indonesia ke public/wilayah (offline)');
