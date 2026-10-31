<?php
// src/Video.php
namespace App;

use PDO;

class Video
{
    public function __construct(private PDO $db) {}

    public function create(array $data): int
    {
        $sql = "INSERT INTO videos (title, slug, youtube_id, description, category_id, duration, level, tags, thumbnail, published)
                VALUES (:title, :slug, :youtube_id, :description, :category_id, :duration, :level, :tags, :thumbnail, :published)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':title'       => $data['title'],
            ':slug'        => $this->uniqueSlug($data['title']),
            ':youtube_id'  => $data['youtube_id'],
            ':description' => $data['description'] ?? '',
            ':category_id' => $data['category_id'] ?? null,
            ':duration'    => $data['duration'] ?? null,
            ':level'       => $data['level'] ?? 'debutant',
            ':tags'        => $data['tags'] ?? null,
            ':thumbnail'   => $data['thumbnail'] ?? YouTubeHelper::thumbnail($data['youtube_id']),
            ':published'   => $data['published'] ?? 1,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE slug = :slug AND published = 1");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function listAll(int $page = 1, int $perPage = 12, ?string $search = null, ?int $categoryId = null): array
    {
        $offset = ($page - 1) * $perPage;
        $where = "WHERE v.published = 1";
        $params = [];

        if ($search !== null && $search !== '') {
            // LIKE sur 3 colonnes avec 3 placeholders distincts
            // (EMULATE_PREPARES = false interdit la réutilisation d'un placeholder)
            $where .= " AND (v.title LIKE :s1 OR v.description LIKE :s2 OR v.tags LIKE :s3)";
            $like = '%' . $search . '%';
            $params[':s1'] = $like;
            $params[':s2'] = $like;
            $params[':s3'] = $like;
        }
        if ($categoryId) {
            $where .= " AND v.category_id = :cat";
            $params[':cat'] = $categoryId;
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS v.*, c.name AS category_name, c.slug AS category_slug
                FROM videos v
                LEFT JOIN categories c ON v.category_id = c.id
                {$where}
                ORDER BY v.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        $total = (int)$this->db->query("SELECT FOUND_ROWS()")->fetchColumn();

        return ['items' => $items, 'total' => $total, 'page' => $page, 'perPage' => $perPage];
    }

    public function incrementViews(int $id): void
    {
        $this->db->prepare("UPDATE videos SET views = views + 1 WHERE id = :id")
                 ->execute([':id' => $id]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE videos SET title=:title, description=:description, category_id=:category_id,
                level=:level, tags=:tags, published=:published WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? '',
            ':category_id' => $data['category_id'] ?? null,
            ':level'       => $data['level'] ?? 'debutant',
            ':tags'        => $data['tags'] ?? null,
            ':published'   => $data['published'] ?? 1,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM videos WHERE id = :id")->execute([':id' => $id]);
    }

    private function uniqueSlug(string $title): string
    {
        $slug = self::slugify($title);
        $base = $slug;
        $i = 1;
        while (true) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM videos WHERE slug = :s");
            $stmt->execute([':s' => $slug]);
            if ($stmt->fetchColumn() == 0) return $slug;
            $slug = $base . '-' . (++$i);
        }
    }

    public static function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $text));
        return trim($text, '-');
    }
}