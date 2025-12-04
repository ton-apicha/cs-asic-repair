<?php

namespace App\Libraries;

/**
 * Docker Manager Library
 * Manages Docker containers via docker-compose commands
 */
class DockerManager
{
    private string $projectPath;

    public function __construct(string $projectPath = '/var/www/cs-asic-repair')
    {
        $this->projectPath = $projectPath;
    }

    /**
     * Get all containers status
     */
    public function getContainers(): array
    {
        $output = $this->execCommand('docker compose ps --format json');

        if (empty($output)) {
            return [];
        }

        $containers = [];
        $lines = explode("\n", trim($output));

        foreach ($lines as $line) {
            if (empty($line)) continue;

            $data = json_decode($line, true);
            if ($data) {
                $containers[] = [
                    'name' => $data['Name'] ?? '',
                    'service' => $data['Service'] ?? '',
                    'status' => $data['State'] ?? '',
                    'health' => $data['Health'] ?? 'none',
                    'ports' => $data['Publishers'] ?? [],
                ];
            }
        }

        return $containers;
    }

    /**
     * Restart a container
     */
    public function restartContainer(string $service): array
    {
        $output = $this->execCommand("docker compose restart {$service}");

        return [
            'success' => strpos($output, 'Restarted') !== false || strpos($output, 'Started') !== false,
            'output' => $output,
        ];
    }

    /**
     * Stop a container
     */
    public function stopContainer(string $service): array
    {
        $output = $this->execCommand("docker compose stop {$service}");

        return [
            'success' => strpos($output, 'Stopped') !== false,
            'output' => $output,
        ];
    }

    /**
     * Start a container
     */
    public function startContainer(string $service): array
    {
        $output = $this->execCommand("docker compose start {$service}");

        return [
            'success' => strpos($output, 'Started') !== false,
            'output' => $output,
        ];
    }

    /**
     * Get container logs
     */
    public function getLogs(string $service, int $lines = 100): string
    {
        return $this->execCommand("docker compose logs --tail={$lines} {$service}");
    }

    /**
     * Get container stats
     */
    public function getStats(): array
    {
        $output = $this->execCommand('docker stats --no-stream --format "{{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.NetIO}}"');

        $stats = [];
        $lines = explode("\n", trim($output));

        foreach ($lines as $line) {
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            if (count($parts) >= 4) {
                $stats[] = [
                    'name' => $parts[0],
                    'cpu' => $parts[1],
                    'memory' => $parts[2],
                    'network' => $parts[3],
                ];
            }
        }

        return $stats;
    }

    /**
     * Execute docker compose command
     */
    private function execCommand(string $command): string
    {
        $fullCommand = "cd {$this->projectPath} && {$command} 2>&1";

        exec($fullCommand, $output, $returnCode);

        return implode("\n", $output);
    }
}
