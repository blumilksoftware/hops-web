import { Alpine } from 'alpinejs';
import type { AxiosInstance } from "axios";

declare global {
    interface Window {
        Alpine: Alpine;
        axios: AxiosInstance;
    }
}
