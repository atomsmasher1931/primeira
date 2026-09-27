#!/usr/bin/env bash
# Проверки дневника изменений docs/features/ для хуков Claude Code.
#
#   features-check.sh index          — сверить статусы индекса README.md со статусами файлов
#   features-check.sh merged         — открытые записи (🟡/⏸️), чья ветка уже влита в master
#   features-check.sh pre-bash       — PreToolUse-хук на Bash (JSON на stdin):
#                                      git commit → блок при расхождении индекса,
#                                      git push   → напоминание спросить о закрытии записи
#   features-check.sh session-start  — SessionStart-хук: вывести результат `merged`

set -u

ROOT="${CLAUDE_PROJECT_DIR:-$(git rev-parse --show-toplevel 2>/dev/null || pwd)}"
DIR="$ROOT/docs/features"
INDEX="$DIR/README.md"

# Первый символ-эмодзи статуса из строки «**Статус:** ✅ Готово \».
file_status() {
	grep -m1 '^\*\*Статус:\*\*' "$1" | sed -E 's/^\*\*Статус:\*\* *//' | awk '{print $1}'
}

file_branch() {
	grep -m1 '^\*\*Ветка:\*\*' "$1" | sed -E 's/^[^`]*`([^`]+)`.*$/\1/'
}

feature_files() {
	find "$DIR" -maxdepth 1 -name '20*.md' | sort
}

check_index() {
	local errors="" f name row index_status status
	for f in $(feature_files); do
		name="$(basename "$f")"
		status="$(file_status "$f")"
		row="$(grep -E '^\| *20' "$INDEX" | grep -F "]($name)" | head -1)"
		if [ -z "$row" ]; then
			errors="${errors}- $name: нет строки в индексе README.md\n"
			continue
		fi
		index_status="$(printf '%s' "$row" | awk -F'|' '{print $4}' | awk '{print $1}')"
		if [ "$index_status" != "$status" ]; then
			errors="${errors}- $name: в файле «${status}», в индексе «${index_status}»\n"
		fi
	done
	if [ -n "$errors" ]; then
		printf 'Индекс docs/features/README.md расходится с файлами:\n%b' "$errors" >&2
		return 1
	fi
	return 0
}

merged_branches() {
	git -C "$ROOT" log master origin/master --merges --format='%s' 2>/dev/null \
		| sed -nE 's/^Merge pull request #[0-9]+ from [^/]+\/(.+)$/\1/p' | sort -u
}

check_merged() {
	local merged f status branch out=""
	merged="$(merged_branches)"
	for f in $(feature_files); do
		status="$(file_status "$f")"
		[ "$status" = "🟡" ] || [ "$status" = "⏸️" ] || continue
		branch="$(file_branch "$f")"
		[ -n "$branch" ] || continue
		if printf '%s\n' "$merged" | grep -qxF "$branch"; then
			out="${out}- docs/features/$(basename "$f") (ветка \`$branch\` влита в master, статус ${status})\n"
		fi
	done
	[ -n "$out" ] && printf '%b' "$out"
	return 0
}

pre_bash() {
	local cmd current open_here=""
	cmd="$(jq -r '.tool_input.command // ""')"

	if printf '%s' "$cmd" | grep -qE '(^|[;&|[:space:]])git commit'; then
		if ! check_index; then
			echo "Коммит заблокирован: исправьте индекс (статус в строке индекса должен совпадать со «**Статус:**» в файле)." >&2
			exit 2
		fi
	fi

	if printf '%s' "$cmd" | grep -qE '(^|[;&|[:space:]])git push'; then
		current="$(git -C "$ROOT" branch --show-current)"
		for f in $(feature_files); do
			[ "$(file_status "$f")" = "🟡" ] || continue
			[ "$(file_branch "$f")" = "$current" ] && open_here="$open_here docs/features/$(basename "$f")"
		done
		if [ -n "$open_here" ]; then
			jq -n --arg ctx "Перед push ветки $current открыты записи дневника:$open_here. Спросите пользователя, закрывать ли задачу в этой ветке (✅/⏸️/❌: чистка черновика, git diff --stat, «Обновлено», строка индекса) или оставить 🟡." \
				'{hookSpecificOutput: {hookEventName: "PreToolUse", additionalContext: $ctx}}'
		fi
	fi
	exit 0
}

case "${1:-index}" in
	index) check_index ;;
	merged) check_merged ;;
	pre-bash) pre_bash ;;
	session-start)
		out="$(check_merged)"
		if [ -n "$out" ]; then
			printf "Открытые записи docs/features/, чья ветка уже влита в master — предложите пользователю закрыть их:\n%b" "$out"
		fi
		;;
	*) echo "usage: $0 [index|merged|pre-bash|session-start]" >&2; exit 1 ;;
esac
