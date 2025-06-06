const settings = {
    store: {
        hiddenPosts: [],
        threadList: true,
        hiddenBoards: [],
        isDark: false,
    },
    setHiddenPost(postId, isHidden) {
        if (isHidden) {
            this.store.hiddenPosts.push(postId);
        } else {
            this.store.hiddenPosts = this.store.hiddenPosts.filter(
                (id) => id != postId
            );
        }
        save();
    },
    isHiddenPost(postId) {
        return this.store.hiddenPosts.indexOf(postId) !== -1;
    },
    setThreadListVisibility(isVisible) {
        this.store.threadList = isVisible;
        save();
    },
    isThreadListVisible() {
        return this.store.threadList;
    },
    toggleDarkTheme() {
        this.store.isDark = !this.store.isDark;
        document.documentElement.classList.toggle("dark", settings.store.isDark);
        save();
    },
    isDark() {
        return this.store.isDark;
    },
    setHiddenBoard(boardDir) {
        if (!this.store.hiddenBoards.includes(boardDir)) {
            this.store.hiddenBoards.push(boardDir);
        } else {
            this.store.hiddenBoards = this.store.hiddenBoards.filter(
                (d) => d !== boardDir
            );
        }
        save();
    },
    isBoardHidden(boardDir) {
        return this.store.hiddenBoards.includes(boardDir);
    },
};

function save() {
    window.localStorage.setItem("userSettings", JSON.stringify(settings.store));
}

function load() {
    let stored = window.localStorage.getItem("userSettings");
    if (stored) {
        settings.store = JSON.parse(stored);
    }
}

load();
export default settings;
