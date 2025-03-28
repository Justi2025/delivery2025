/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import AuthenticationService from "./AuthService.js";
import FailServis from "./FailServis.js";
import ServisPolzovatelei from "./ServisPolzovatelei.js";
import ServisDeistviiPolzovatelei from "./ServisDeistviiPolzovatelei.js";
import ServisGorodov from "./ServisGorodov.js";
import ServisKlientov from "./ServisKlientov.js";
const BASE_URL = import.meta.env.VITE_APP_URL_API;

const authService = () => new AuthenticationService(BASE_URL);

const filesService = new FailServis(BASE_URL);
const usersService = () => new ServisPolzovatelei(BASE_URL);
const userActivityService = () => new ServisDeistviiPolzovatelei(BASE_URL);
const citiesService = () => new ServisGorodov(BASE_URL);
const servicePolzovatelei = () => new ServisKlientov(BASE_URL);

export {
    authService,
    filesService,
    usersService,
    userActivityService,
    citiesService,
    servicePolzovatelei,
};
